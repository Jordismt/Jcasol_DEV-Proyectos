import flet as ft
import random

class App:
    def __init__(self, total_num) -> None:
        self.total_num = total_num
        self.num_a_trobar = random.randint(0, self.total_num)
        ft.app(target=self.main)

    def main(self, page: ft.Page):
        self.page = page
        page.title = "Troba el numero"
        page.update()
        page.add(ft.Text("Numeros"))
        llista_nums = []
        print(self.num_a_trobar)
        
        def comprovar_num(num):
            
            if num == self.num_a_trobar:
                print("Enhorabona")
                page.add(ft.Text("Enhorabona, has acertat el numero"))
                page.update(
                    ft.Container(
                        bgcolor=ft.colors.GREEN
                    )
                )
            else:
                print("No es el num")
                page.add(ft.Text("No es el numero"))
                page.update(
                    ft.Container(
                        bgcolor=ft.colors.RED
                    )
                )
                
        def items(count): 
            for i in range(1, count ):
                llista_nums.append(
                    ft.Container(
                        content=ft.Text(f"Numero {i}"),
                        margin=10,
                        padding=10,
                        alignment=ft.alignment.center,
                        bgcolor=ft.colors.AMBER,
                        width=120,
                        height=120,
                        border_radius=10,
                        ink=True,
                        on_click=lambda i=i: comprovar_num(i),
                    )
                )
            

            return llista_nums

        width_slider = ft.Slider(
            min=0,
            max=page.window_width,
            divisions=20,
            value=page.window_width,
            label="{value}",
        )

        row = ft.Row(
            wrap=True,
            spacing=10,
            run_spacing=10,
            controls=items(self.total_num),
            width=page.window_width,
        )
        page.add(row)

if __name__ == "__main__":
    app = App(10)
