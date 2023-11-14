import flet as ft

def main(page):
    def add_clicked(e):
        page.add(ft.Checkbox(label=new_task.value))
        new_task.value = ""
        new_task.focus()
        boto_desact.disabled=True
        page.update()

    def check_value(e):
        boto_desact.disabled=new_task.value==''
        boto_desact.update()

    new_task = ft.TextField(hint_text="Tasca pendent", width=300, on_change=check_value)
    boto_desact=ft.ElevatedButton("Afegir", on_click=add_clicked, disabled=True)
    page.add(ft.Row([new_task, boto_desact]))
    
ft.app(target=main)