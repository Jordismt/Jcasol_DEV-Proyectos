import pygame
import random

# Inicializar pygame
pygame.init()

# Definir los colores
WHITE = (255, 255, 255)
RED = (255, 0, 0)
GREEN = (0, 255, 0)
BLACK = (0, 0, 0)

# Definir el tamaño de la pantalla
WIDTH, HEIGHT = 600, 400
CELL_SIZE = 20

# Definir el estado del juego
class GameState:
    MENU = 0
    PLAYING = 1
    GAME_OVER = 2

# Función para dibujar el menú
def draw_menu(screen):
    screen.fill(BLACK)
    font = pygame.font.Font(None, 36)
    title_text = font.render("Snake Game", True, WHITE)
    title_rect = title_text.get_rect(center=(WIDTH // 2, HEIGHT // 2 - 50))
    screen.blit(title_text, title_rect)
    start_text = font.render("Press SPACE to Play", True, WHITE)
    start_rect = start_text.get_rect(center=(WIDTH // 2, HEIGHT // 2 + 50))
    screen.blit(start_text, start_rect)

# Función para dibujar la serpiente y la manzana en la pantalla
def draw_objects(screen):
    screen.fill(BLACK)
    for segment in snake:
        pygame.draw.rect(screen, GREEN, (segment[0], segment[1], CELL_SIZE, CELL_SIZE))
    pygame.draw.rect(screen, RED, (apple[0], apple[1], CELL_SIZE, CELL_SIZE))
    for obstacle in obstacles:
        pygame.draw.rect(screen, WHITE, (obstacle[0], obstacle[1], CELL_SIZE, CELL_SIZE))

# Función para mover la serpiente
def move_snake():
    global dx, dy, apple, score, snake_speed

    # Mover la cabeza de la serpiente
    new_head = (snake[0][0] + dx * CELL_SIZE, snake[0][1] + dy * CELL_SIZE)

    # Verificar si la serpiente ha colisionado con sí misma o con un obstáculo
    if new_head in snake[1:] or new_head in obstacles:
        game_over()

    # Verificar si la serpiente ha colisionado con la pared
    if new_head[0] < 0 or new_head[0] >= WIDTH or new_head[1] < 0 or new_head[1] >= HEIGHT:
        game_over()

    # Agregar la nueva cabeza de la serpiente
    snake.insert(0, new_head)

    # Verificar si la serpiente ha comido una manzana
    if new_head == apple:
        score += 1
        apple = (random.randint(0, (WIDTH - CELL_SIZE) // CELL_SIZE) * CELL_SIZE,
                 random.randint(0, (HEIGHT - CELL_SIZE) // CELL_SIZE) * CELL_SIZE)
        snake_speed += 0.5  # Aumentar la velocidad gradualmente
    else:
        snake.pop()

# Función para generar obstáculos aleatorios
def generate_obstacles():
    global obstacles
    obstacles = []
    for _ in range(random.randint(1, 10)):  # Generar obstaculos aleatorios entre 1 i 10
        obstacle = (random.randint(0, (WIDTH - CELL_SIZE) // CELL_SIZE) * CELL_SIZE,
                    random.randint(0, (HEIGHT - CELL_SIZE) // CELL_SIZE) * CELL_SIZE)
        if obstacle != apple and obstacle not in snake:  # Evitar superposición con la manzana y la serpiente
            obstacles.append(obstacle)

# Función para manejar el evento de finalización del juego
def game_over():
    global state
    state = GameState.GAME_OVER

# Bucle principal del juego
screen = pygame.display.set_mode((WIDTH, HEIGHT))
pygame.display.set_caption("Snake Game")
clock = pygame.time.Clock()
state = GameState.MENU
snake_speed = 5  # Velocidad inicial de la serpiente

while True:
    for event in pygame.event.get():
        if event.type == pygame.QUIT:
            pygame.quit()
            quit()
        elif event.type == pygame.KEYDOWN:
            if state == GameState.MENU:
                if event.key == pygame.K_SPACE:
                    snake = [(WIDTH // 2, HEIGHT // 2)]
                    dx, dy = 1, 0
                    apple = (random.randint(0, (WIDTH - CELL_SIZE) // CELL_SIZE) * CELL_SIZE,
                             random.randint(0, (HEIGHT - CELL_SIZE) // CELL_SIZE) * CELL_SIZE)
                    score = 0
                    generate_obstacles()  # Generar obstáculos al comenzar el juego
                    state = GameState.PLAYING
            elif state == GameState.PLAYING:
                if event.key == pygame.K_w and dy == 0:
                    dx, dy = 0, -1
                elif event.key == pygame.K_s and dy == 0:
                    dx, dy = 0, 1
                elif event.key == pygame.K_a and dx == 0:
                    dx, dy = -1, 0
                elif event.key == pygame.K_d and dx == 0:
                    dx, dy = 1, 0
            elif state == GameState.GAME_OVER:
                if event.key == pygame.K_SPACE:
                    state = GameState.MENU

    if state == GameState.MENU:
        draw_menu(screen)
    elif state == GameState.PLAYING:
        move_snake()
        draw_objects(screen)
        font = pygame.font.Font(None, 24)
        score_text = font.render("Score: " + str(score), True, WHITE)
        screen.blit(score_text, (10, 10))
    elif state == GameState.GAME_OVER:
        screen.fill(BLACK)
        font = pygame.font.Font(None, 36)
        game_over_text = font.render("Game Over", True, WHITE)
        game_over_rect = game_over_text.get_rect(center=(WIDTH // 2, HEIGHT // 2))
        screen.blit(game_over_text, game_over_rect)
        retry_text = font.render("Press SPACE to Play Again", True, WHITE)
        retry_rect = retry_text.get_rect(center=(WIDTH // 2, HEIGHT // 2 + 50))
        screen.blit(retry_text, retry_rect)

    pygame.display.flip()
    clock.tick(snake_speed)

