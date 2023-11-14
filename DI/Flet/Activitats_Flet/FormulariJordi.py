import flet as ft

def main(page):
    def check_password(passwd):
        list_special_character=["/","*","-","+","{","}","(",")","[","]","@","#","~","$","&","=","?","¿","¡","<",">"]
        if passwd.size > 8:
            return False
        for x in list_special_character:
            if x in passwd:
                return False
        for x in passwd:
            if x.isupper():
                return True
            if x.islower():
                return True
            if x.isdigit():
                return True
        if passwd:
                submit_btn.disabled=False
    def button_clicked(e):
        output_text.value = f"Dropdown value is:  {color_dropdown.value}"
        page.update()
    output_text = ft.Text()
    
    color_dropdown = ft.Dropdown(
        label="Provincia",
        width=200,
        border_radius=25,
        options=[
            ft.dropdown.Option("Valencia"),
            ft.dropdown.Option("Madrid"),
            ft.dropdown.Option("Barcelona"),
            ft.dropdown.Option("Muscia"),
            ft.dropdown.Option("Lugo"),
            ft.dropdown.Option("Alacant"),
            ft.dropdown.Option("Castello"),
            ft.dropdown.Option("Sevilla"),
            ft.dropdown.Option("Malaga"),
            ft.dropdown.Option("Huelva"),


        ],
        
    )
    text_benvunguda=ft.Text(value="FORMULARI DE REGISTRE")
    dp=ft.Text(value="Dades personals")
    text_nom = ft.TextField(label="Nombre", hint_text="Escriu el teu nom", border_radius=25,width=500 )
    text_Direccio=ft.TextField(label="Direcció", hint_text="Escriu la direcció",border_radius=25,width=500)
    text_password=ft.TextField(label="Password", hint_text="Escriu contrasenya",border_radius=25,width=500, password=True, can_reveal_password=True)
    submit_btn = ft.ElevatedButton(text="Enviar", disabled=True)
    page.update()
    page.add(text_benvunguda,dp,text_nom,text_Direccio, text_password, color_dropdown, output_text, submit_btn)
    
ft.app(target=main)
