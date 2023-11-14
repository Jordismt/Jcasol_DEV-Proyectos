import pygame
from pygame.locals import *
import random
import os
try:
    #Iniciar pygame i mixer
    pygame.init()
    pygame.mixer.init()
    # Configuració de la pantalla
    SCREEN_WIDTH = 800
    SCREEN_HEIGHT = 600

    pantalla = pygame.display.set_mode([SCREEN_WIDTH, SCREEN_HEIGHT])


    #SONIDO

    ruta_so_dalt=os.path.dirname(__file__)
    move_up_sound=pygame.mixer.Sound(os.path.join(ruta_so_dalt, "Rising_putter.ogg"))

    ruta_so_baix=os.path.dirname(__file__)
    move_down_sound=pygame.mixer.Sound(os.path.join(ruta_so_baix, "Falling_putter.ogg"))

    ruta_so_colicio=os.path.dirname(__file__)
    collision_sound=pygame.mixer.Sound(os.path.join(ruta_so_colicio, "Collision.ogg"))

    # colors fondo
    COLOR_DIA = (135, 206, 250)  # Blau
    COLOR_NOCHE = (0, 0, 0)  # Negre

    #Estat del joc
    ESTAT_BENVINGUDA = 0
    ESTAT_JOC = 1
    estat_joc = ESTAT_BENVINGUDA

    #Estat fi del joc
    ESTAT_FINAL=2
    # Variable per a controlar el cicle día/nit
    es_dia = True

    # Crear clases
    class Jugador(pygame.sprite.Sprite):
        def __init__(self):
            super(Jugador, self).__init__()
            ruta_base = os.path.dirname(__file__)
            ruta_a_recurs = os.path.join(ruta_base, "jet.png")
            self.surf = pygame.image.load(ruta_a_recurs).convert()
            self.surf.set_colorkey((255, 255, 255), RLEACCEL)
            self.rect = self.surf.get_rect()

        def update(self, pressed_keys):
            if pressed_keys[K_UP]:
                self.rect.move_ip(0, -5)
            if pressed_keys[K_DOWN]:
                self.rect.move_ip(0, 5)
            if pressed_keys[K_LEFT]:
                self.rect.move_ip(-5, 0)
            if pressed_keys[K_RIGHT]:
                self.rect.move_ip(5, 0)

            if self.rect.left < 0:
                self.rect.left = 0
            if self.rect.right > SCREEN_WIDTH:
                self.rect.right = SCREEN_WIDTH
            if self.rect.top <= 0:
                self.rect.top = 0
            if self.rect.bottom >= SCREEN_HEIGHT:
                self.rect.bottom = SCREEN_HEIGHT

    class Enemic(pygame.sprite.Sprite):
        def __init__(self):
            super(Enemic, self).__init__()
            ruta_base_misil = os.path.dirname(__file__)
            ruta_a_recurs_misil = os.path.join(ruta_base_misil, "missile.png")
            self.surf = pygame.image.load(ruta_a_recurs_misil).convert()
            self.surf.set_colorkey((255, 255, 255), RLEACCEL)
            self.rect = self.surf.get_rect()
            self.rect = self.surf.get_rect(
                center=(
                    random.randint(SCREEN_WIDTH + 20, SCREEN_WIDTH + 100),
                    random.randint(0, SCREEN_HEIGHT),
                )
            )
            self.speed = random.randint(5, 20)

        def update(self):
            self.rect.move_ip(-self.speed, 0)
            if self.rect.right < 0:
                self.kill()

    class Nuvol(pygame.sprite.Sprite):
        def __init__(self):
            super(Nuvol, self).__init__()
            ruta_base_nuvol = os.path.dirname(__file__)
            ruta_a_recurs_nuvol = os.path.join(ruta_base_nuvol, "cloud.png")
            self.surf = pygame.image.load(ruta_a_recurs_nuvol).convert()
            self.surf.set_colorkey((0, 0, 0), RLEACCEL)
            self.rect = self.surf.get_rect()
            self.rect.x = SCREEN_WIDTH  # Comença fora de la pantalla dreta
            self.rect.y = random.randint(0, SCREEN_HEIGHT)  # Posició vertical aleatoria
            self.speed = 5

        def update(self):
            self.rect.move_ip(-self.speed, 0)
            if self.rect.right < 0:
                self.rect.left = SCREEN_WIDTH  # Reinicia la posició en la pantalla

    clock = pygame.time.Clock()
    all_sprites = pygame.sprite.Group()
    nubes = pygame.sprite.Group()
    enemic = pygame.sprite.Group()


    # JUGADOR
    jugador = Jugador()
    all_sprites.add(jugador)

    # NUVOL
    ADD_CLOUD = pygame.USEREVENT + 2
    pygame.time.set_timer(ADD_CLOUD, 1000)  # Apareix un nuvol cada 1s

    # ENEMIC
    ADD_ENEMY = pygame.USEREVENT + 1
    pygame.time.set_timer(ADD_ENEMY, 250)

    # Sistema de puntuació i nivell
    score = 0
    font = pygame.font.Font(None, 36)

    nivel = 1
    puntaje_requerido = 500

    font_nivel = pygame.font.Font(None, 36)

    def show_score():
        score_text = font.render(f"Puntuació: {score}", True, (255, 255, 255))
        pantalla.blit(score_text, (10, 10))

    def show_nivel():
        nivel_texto = font_nivel.render(f"Nivell: {nivel}", True, (255, 255, 255))
        pantalla.blit(nivel_texto, (10, 50))

    def aumentar_nivel():
        global nivel, puntaje_requerido
        nivel += 1
        puntaje_requerido = nivel * 500

    def aumentar_dificultat():
        vel_creacio_enemic=100+(ADD_ENEMY-50*nivel)
        vel_desplasament_enemic=(2*nivel)+(10+3*nivel)

    #Millor puntuacio
    millor_puntuacio = 0
    if os.path.exists("punt_max.txt"):
        with open("punt_max.txt", "r") as file:
            millor_puntuacio = int(file.read())

    pygame.display.flip()

    # Temporizador per a controlar el cicle día/nit
    temporizador_dia_noche = pygame.time.get_ticks()
    TIEMPO_DIA_NOCHE = 20000  # Cambia cada 20 segons 

    def reiniciar_joc():
        global estat_joc, score, nivel
        estat_joc = ESTAT_BENVINGUDA
        score = 0
        nivel = 1
        # Restaura la velocitat d'aparició d'enemics
        global velocidad_creacion_enemigos, velocidad_minima_enemigos, velocidad_maxima_enemigos
        velocidad_creacion_enemigos = 500
        velocidad_minima_enemigos = 1
        velocidad_maxima_enemigos = 10
        global jugador
        jugador = Jugador()
        all_sprites.add(jugador)
        
    # Bucle principal
    jugar = True
    while jugar:
        for event in pygame.event.get():
            if event.type == pygame.QUIT:
                jugar = False
            elif event.type == KEYDOWN:
                if event.key == K_ESCAPE:
                    jugar = False
                elif event.key==K_UP:
                    move_up_sound.play()
                elif event.key==K_DOWN:
                    move_down_sound.play()
                elif estat_joc == ESTAT_BENVINGUDA and event.key == K_p:
                    estat_joc = ESTAT_JOC
                elif estat_joc == ESTAT_FINAL and event.key == K_r:
                    # Reiniciar el joc quan es pulse "r" després d'una partida acabada
                    reiniciar_joc()
            
                    
                    
            elif event.type == ADD_ENEMY:
                new_enemy = Enemic()
                enemic.add(new_enemy)
                aumentar_dificultat()
                all_sprites.add(new_enemy)
            elif event.type == ADD_CLOUD:
                new_cloud = Nuvol()
                nubes.add(new_cloud)
                all_sprites.add(new_cloud)


        if estat_joc == ESTAT_BENVINGUDA:
            # Pantalla de benvinguda
            pantalla.fill(COLOR_DIA if es_dia else COLOR_NOCHE)
            bienvenida_texto = font.render("Benvingut al Joc!", True, (255, 255, 255))
            record_texto = font.render(f"Rècord fins al moment: {millor_puntuacio}", True, (255, 255, 255))
            iniciar_texto = font.render("Pulsa 'p' per començar", True, (255, 255, 255))
            pantalla.blit(bienvenida_texto, (250, 200))
            pantalla.blit(record_texto, (250, 250))
            pantalla.blit(iniciar_texto, (250, 300))
            pygame.display.flip()

        elif estat_joc == ESTAT_JOC:
            for entity in all_sprites:
                pantalla.blit(entity.surf, entity.rect)
            pantalla.blit(jugador.surf, jugador.rect)
            if pygame.sprite.spritecollideany(jugador, enemic):
                collision_sound.play()
                jugador.kill()
                estat_joc = ESTAT_FINAL
        
            # Comprobar si cambia de temps
            tiempo_actual = pygame.time.get_ticks()
            if tiempo_actual - temporizador_dia_noche >= TIEMPO_DIA_NOCHE:
                es_dia = not es_dia
                temporizador_dia_noche = tiempo_actual

                # Cambia el color 
                if es_dia:
                    pantalla.fill(COLOR_DIA)
                else:
                    pantalla.fill(COLOR_NOCHE)
            for enemy in enemic:
                if enemy.rect.left < 0:
                    score += 10  # Aumenta la puntuació per cada misil que pase de l'esquerra

                if score >= puntaje_requerido:
                    aumentar_nivel()  # Aumentar el nivell 
        elif estat_joc == ESTAT_FINAL:
            # Pantalla final
            pantalla.fill(COLOR_DIA if es_dia else COLOR_NOCHE)
            final_texto = font.render("Partida Finalitzada", True, (255, 255, 255))
            puntuacio_texto = font.render(f"Puntuació: {score}", True, (255, 255, 255))
            nivell_texto = font.render(f"Nivell aconseguit: {nivel}", True, (255, 255, 255))
            reiniciar_texto = font.render("Pulsa 'r' per reiniciar", True, (255, 255, 255))
            
            if score > millor_puntuacio:
                millor_puntuacio = score
                millor_puntuacio_texto = font.render("Felicitats! Has aconseguit un nou rècord!", True, (255, 255, 255))
                pantalla.blit(millor_puntuacio_texto, (200, 400))
                # Guardar la nova puntuació màxima al document
                with open("punt_max.txt", "w") as file:
                    file.write(str(score))

            pantalla.blit(final_texto, (300, 200))
            pantalla.blit(puntuacio_texto, (300, 250))
            pantalla.blit(nivell_texto, (300, 300))
            pantalla.blit(reiniciar_texto, (250, 350))
            pygame.display.flip()

        else:
            pygame.time.delay(3000)  # Esperar 3 segons abans de tancar el joc
            jugar = False  # Sortir del bucle de joc
            pygame.display.flip()
        pressed_keys = pygame.key.get_pressed()
        jugador.update(pressed_keys)
        enemic.update()
        nubes.update()

        show_score()  #Mostrar puntuacio en pantalla
        show_nivel()  # Mostrar nivell en pantalla

        pygame.display.flip()
        pantalla.fill(COLOR_DIA if es_dia else COLOR_NOCHE)
        clock.tick(30)

    #Tancar sonido
    pygame.mixer.music.stop()
    pygame.mixer.quit()

    # Ixir
    pygame.quit()
except:
    print("Hi ha algun error o no s'han carregat les imatges")
    