import tkinter.ttk
from tkinter import *
from PIL import Image, ImageTk
from datetime import *
import time
import ctypes
import sqlite3
import matplotlib.pyplot as plt
from matplotlib.backends.backend_tkagg import FigureCanvasTkAgg
import tkinter as tk
from tkinter import ttk, Text, WORD, END, INSERT, filedialog, simpledialog, messagebox
import os
import calendar

'''Notas:
#FD9C3A -> Laranja 
#17191F -> Cinzento Escuro
#2E3133 -> Cinzento Claro

#TENTAR FAZER FUNÇÃO QUE ORDENE OS ITENS DA TREEVIEW
#FAZER CAMPO PARA SABER SE A ENCOMENDA FOI ENTREGUE OU NÃO E METEDO DE PAGAMENTO 
#FAZER CENA PARA ORDENAR AS ENCOMENDAS (MES E CENAS ASSIM)
#ADICIONAR CAMPO PARA SABER SE PODE SER FRITO OU NAO'''

class Funcs:

    #funções para o ecra principal
    def conecta_bd(self):  # faz conexao á base de dados
        self.conn = sqlite3.connect("../BD/Rissois.db")
        self.cursor = self.conn.cursor()
    def desconecta_bd(self):  # desliga a conexao á base de dados e confirma o script feito
        self.conn.commit()
        self.conn.close()
    def contar_clientes(self):  # função pra contar o numero de clientes
        self.conecta_bd()
        resultado = self.cursor.execute("SELECT COUNT(*) FROM Clientes").fetchone()
        self.desconecta_bd()
        return resultado[0]
    def contar_Produtos(self): #função para contar o numero de produtos
        self.conecta_bd()
        resultado = self.cursor.execute("SELECT COUNT(*) FROM Produtos").fetchone()
        self.desconecta_bd()
        return resultado[0]
    def contar_lucro(self):# função que vai buscar o lucro por mes
        self.conecta_bd()
        resultado = self.cursor.execute("""SELECT strftime('%Y-%m', E."data_encomenda") AS month, SUM(LE."preco_produto" * LE."quantidade") AS total_amount FROM "Encomendas" AS E JOIN "Linha_de_Encomenda" AS LE ON E."id_Encomendas" = LE."Encomendas_id_Encomendas" GROUP BY month ORDER BY month;""").fetchall()
        self.desconecta_bd()
        return resultado
    def contar_Encomendas(self):  # função para contar o numero de encomendas
        self.conecta_bd()
        resultado = self.cursor.execute("SELECT COUNT(*) FROM Encomendas").fetchone()
        self.desconecta_bd()
        return resultado[0]
    def grafico(self):#faz o grafico com o lucro mensal
        for widget in self.bodyFrame1_Inicio.winfo_children():#Apaga tudo antes de atualizar o grafico
            widget.destroy()
        ### Grafico
        #### Configs do Grafico
        fig, ax = plt.subplots(figsize=(8, 4), tight_layout=True, facecolor='#2E3133')
        ax.set_facecolor('#2E3133')
        ax.spines['bottom'].set_color('white')
        ax.spines['top'].set_color('white')
        ax.spines['right'].set_color('white')
        ax.spines['left'].set_color('white')

        #### Valores do Grafico
        lucros = self.contar_lucro()
        mes, total = zip(*lucros)

        #### Cores da escala
        ax.xaxis.label.set_color('white')
        ax.yaxis.label.set_color('white')
        for text in ax.get_xticklabels() + ax.get_yticklabels():
            text.set_color('white')

        #### Cores dos eixos
        ax.bar(mes, total, color='#FD9C3A', )
        ax.set_xlabel('Mês', color='white')
        ax.set_ylabel('Total ', color='white')
        ax.set_title('Total por Mês', color='white')

        #### Escreve os meses
        ax.set_xticks(range(len(mes)))
        ax.set_xticklabels(mes, rotation=45, ha='right')

        #### Por o Grafico no BodyFrame1
        canvas = FigureCanvasTkAgg(fig, master=self.bodyFrame1_Inicio)
        canvas.draw()
        canvas.get_tk_widget().pack(side=TOP, fill=BOTH, expand=1)
        for text in ax.get_xticklabels() + ax.get_yticklabels():
            text.set_color('white')
    # funções para os Produtos
    def lista_produtos(self):#faz o select que vai mostrar os produtos e as suas informações e que depois mete na treeview
        self.produtos_lista.delete(*self.produtos_lista.get_children())
        self.conecta_bd()
        lista = self.cursor.execute("SELECT id_produto, nome_produto, preco || ' €' AS preco,desc, caminho_imagem, CASE WHEN ativo = 1 THEN 'Sim' ELSE 'Não' END AS ativo FROM Produtos ORDER BY id_produto;")
        for i in lista:
            self.produtos_lista.insert("", "end", values=i)
            self.produtos_lista.update()
        self.desconecta_bd()
    def on_double_click(self, event):# Quando se faz doublee click as entrys preenchem automaticamente com os produtos em que o double click foi feito
        #buscar o valor que esta selecionado (onde foi o double click)
        item = self.produtos_lista.selection()[0]
        values = self.produtos_lista.item(item, "values")

        #substitui os valores das entrys pelos valores onde o double click foi
        self.Textbox_Produtos.delete(0, tk.END)
        self.Textbox_Produtos.insert(0, values[1])
        self.Textbox_Preco.delete(0, tk.END)
        self.Textbox_Preco.insert(0, values[2][:-1])
        self.TextBox_Descrição.delete(1.0, tk.END)
        self.TextBox_Descrição.insert(tk.END, values[3])

        #caminho da imagem = values[4]

        try:# este try serve para que não ocorra um erro quando se vai buscar a imagem á base de dados

            ImagemProduto = ImageTk.PhotoImage(Image.open(values[4]).resize((250, 135)))
            #se o try nao der e erro e houver uma imagem antes, essa mesma imagem é trocada por outra e o texto do botao é mudado
            self.nova_imagem.config(text="Alterar Imagem")
            self.logo.config(image=ImagemProduto)
            self.logo.image = ImagemProduto

        except FileNotFoundError: #se na base de dados não estiver nenhuma imagem/houver um erro a ir buscar uma imagem vai trocar a imagem e o texto do botão

            ImagemProduto =ImageTk.PhotoImage(Image.open('../img/semImagem.png').resize((250, 135)))
            hora_erro = datetime.now().strftime("%H:%M:%S")
            print(f"Erro: Imagem não Encontrada - {hora_erro}")
            self.logo.config(image=ImagemProduto)
            self.logo.image = ImagemProduto
            self.nova_imagem.config(text="Adicione uma imagem")
    def Limpar(self):# função que server para limpar as entrys e a seleção da treeview
            resposta = messagebox.askyesno("Confirmação", "Limpar todas as Entrys?")
            if resposta: #Se for Sim vai limpar
                self.Textbox_Produtos.delete(0, END)
                self.Textbox_Preco.delete(0, END)
                self.TextBox_Descrição.delete(1.0, END)
                self.nova_imagem.config(text="Adicione uma imagem")
                self.logo.image = ImageTk.PhotoImage(Image.open('../img/semImagem.png').resize((250, 135)))
                self.produtos_lista.selection_remove(self.produtos_lista.selection())
                if hasattr(self, 'nome_erro'):
                    self.nome_erro.config(text="")
    def on_right_click(self, event):# função que com o botao direito desativa o produto (basicamente torna o produto indesponivel)
        self.conecta_bd()
        item = self.produtos_lista.selection()[0] if self.produtos_lista.selection() else None #vai buscar o valor do produto selecionado

        if item:
            values = self.produtos_lista.item(item, "values")
            novo_estado = 0 if values[5] == "Sim" else 1 #se o estado for Sim o valor é 1 se não é 0
            pergunta = "Queres desativar este produto?" if values[5] == "Sim" else "Queres ativar este produto?" #se o produto estiver ativo pergunta se quer desativar se não pergunta se quer ativar

            if messagebox.askyesno("Confirmação", pergunta):
                resultado = self.cursor.execute("UPDATE Produtos SET ativo = ? WHERE id_produto = ?;",(novo_estado, values[0]))
                self.desconecta_bd()
                self.lista_produtos()

                #atualiza as treeviews
                self.produtos_lista.update()
                self.produtos_encomendas()
    def inserir_imagem(self):#guarda as imagens que foram uploadadas numa pasta com um nome especifico para serem tratadas pela,base de dados
        try:# verifica se esta alguma coisa selecionada. Se tiver vai fazer o id desse produto se nao vai continuar a partir do ultimo numero
            item = self.produtos_lista.item(self.produtos_lista.selection()[0], "values")[0]
        except IndexError:
            item = self.contar_Produtos() + 1

        #vai abrir uma janela para escolher uma imagem
        self.caminho_nova_imagem = filedialog.askopenfilename(initialdir="/",title="Selecione uma imagem",filetypes=(("Arquivos de Imagem", "*.png;*.jpg;*.jpeg;"), ("Todos os arquivos", "*.*")))

        if self.caminho_nova_imagem and not self.caminho_nova_imagem.lower().endswith(('.png', '.jpg', '.jpeg')):#confirmar o tipo do ficheiro
            if hasattr(self, 'nome_erro'):
                self.nome_erro.config(text='Tipo de ficheiro errado')
            else:
                self.nome_erro = Label(self.bodyFrame4_Produtos, bg="#2E3133", text='Tipo de ficheiro errado',font=("", 8, "bold"), fg='red')
                self.nome_erro.place(x=25, y=50)
        else:
            self.nova_imagem.config(text="Alterar imagem")
            nova_imagem = Image.open(self.caminho_nova_imagem).resize((250, 135))
            photo_image = ImageTk.PhotoImage(nova_imagem)
            self.logo.config(image=photo_image)
            self.logo.image = photo_image
    def adicionar_produto(self):# função que vai adicionar os produtos/alterar um produto já existente
            self.conecta_bd()
            #vai bucar o numero do produto selecionado se não vai adicionar um produto indo buscar o numero de produtos
            selecionado = self.produtos_lista.selection()
            item = self.produtos_lista.item(selecionado[0], "values")[0] if selecionado else int(self.contar_Produtos()) + 1
            mensagens_erro = [] #lista com as palavras para a mensagem de erro

            #vai buscar os dados que estão nas entrys , mas se alguma delas estiver vazia ,vai buscar o valor que estava antes na base de dados para nao ocorrer nenhum erro
            try:
                nome = self.Textbox_Produtos.get() or self.cursor.execute("SELECT nome_produto FROM Produtos WHERE id_produto=?", (item,)).fetchone()[0]
                if len(str(nome)) > 45:
                    mensagens_erro.append("Nome")
            except Exception:
                if not selecionado:
                    mensagens_erro.append("Nome")

            try:
                preco = float(self.Textbox_Preco.get()) or float(self.cursor.execute("SELECT preco FROM Produtos WHERE id_produto=?", (item,)).fetchone()[0])
                if len(str(preco).split('.')[0]) > 10 or len(str(preco).split('.')[1]) > 2:
                    mensagens_erro.append("Preço")
            except Exception:
                if not selecionado:
                    mensagens_erro.append("Preço")

            try:
                desc = self.TextBox_Descrição.get("1.0", "end-1c") or self.cursor.execute("SELECT `desc` FROM Produtos WHERE id_produto=?", (item,)).fetchone()[0]
                if len(str(desc)) > 300:
                    mensagens_erro.append("Descrição")
            except Exception:
                if not selecionado:
                    mensagens_erro.append("Descrição")

            try:
                imagem = Image.open(self.caminho_nova_imagem).resize((250, 135))
                imagem.save(os.path.join("../img/", f"imagem_produto_{item}.png"))
                imagem = "../img/" + f"imagem_produto_{item}.png"
            except Exception:
                if selecionado:
                    imagem = self.cursor.execute("SELECT caminho_imagem FROM Produtos WHERE id_produto=?", (item,)).fetchone()[0]
                else:
                    mensagens_erro.append("Imagem")

            if not selecionado and mensagens_erro: #se um produto nao estiver selecionado (significa que nada vai ser atualizado, e que o utilizador vai adicionar um produto) vai criar a mensagem de erro com os erros que apareceram
                if hasattr(self, 'nome_erro'):
                    mensagem_completa = ", ".join(mensagens_erro) + " Invalidos"
                    self.nome_erro.config(text=mensagem_completa)
                    self.nome_erro.place(x=25, y=50)
                else:
                    mensagem_completa = ", ".join(mensagens_erro) + " Invalidos"
                    self.nome_erro = Label(self.bodyFrame4_Produtos, bg="#2E3133", text=mensagem_completa,font=("", 8, "bold"), fg='red')
                    self.nome_erro.place(x=25, y=50)

            if not any(mensagens_erro):# se a mensagem de erro estiver vazia e o wigget estiver criado , o wigget vai ser apagado
                if hasattr(self, 'nome_erro'):
                    self.nome_erro.destroy()

            try: #vai tentar fazer as alterações na base de dados , se não der escreve uma mensagem de erro
                if selecionado: #se algum produto estiver selecionado vai atualizar se não tiver vai adicionar um novo
                    self.conecta_bd()
                    self.cursor.execute("UPDATE Produtos SET nome_produto=?, preco=?, `desc`=?, caminho_imagem=? WHERE id_produto=?",(nome, preco, desc, imagem, item))
                    self.desconecta_bd()
                else:
                    self.conecta_bd()
                    self.cursor.execute("INSERT INTO Produtos (nome_produto, preco, `desc`, caminho_imagem, ativo) VALUES (?, ?, ?, ?, ?)",(nome, preco, desc, imagem, 1))
                    self.desconecta_bd()
                    self.N_produtosFrame3_Produtos.config(text=int(self.contar_Produtos()))
                    self.N_produtosFrame3_Inicio.config(text=int(self.contar_Produtos()))
                    self.Limpar()
            except:
                hora_erro = datetime.now().strftime("%H:%M:%S")
                print(f"Ocorreu um erro ao tentar adicionar/atualizar algo na base dados - {hora_erro}")

            self.logo.image = ImageTk.PhotoImage(Image.open('../img/semImagem.png').resize((250, 135)))
            self.nova_imagem.config(text="Adicione uma imagem")

            self.lista_produtos()
            self.produtos_lista.update()
    #funções para Clientes
    def lista_clientes(self):#faz o select que vai mostrar os clientes e as suas informações  que depois mete na treeview
        self.clientes_lista.delete(*self.clientes_lista.get_children())
        self.conecta_bd()
        lista = self.cursor.execute("SELECT * FROM Clientes")
        for i in lista:
            self.clientes_lista.insert("", "end", values=i)
            self.clientes_lista.update()
        self.desconecta_bd()
    def lista_clientes_encomendas(self):#função que mostra as pessoas que fizeram mais encomendas
        self.clientes_encomendas_lista.delete(*self.clientes_encomendas_lista.get_children())
        self.conecta_bd()
        lista = self.cursor.execute("SELECT C.id_clientes, C.nome_cliente, COUNT(E.id_Encomendas) AS total_encomendas FROM Clientes C LEFT JOIN Encomendas E ON C.id_clientes = E.id_clientes GROUP BY C.id_clientes, C.username, C.nome_cliente ORDER BY total_encomendas DESC;")
        for i in lista:
            self.clientes_encomendas_lista.insert("", "end", values=i)
            self.clientes_encomendas_lista.update()
        self.desconecta_bd()
    #funções para as Encomendas
    def lista_nome_clientes(self):# Função que faz uma lista apenas com os nomes dos clientes para depois selecionar e fazer uma encomenda
        self.clientes_lista_encomendas.delete(*self.clientes_lista_encomendas.get_children())
        self.conecta_bd()
        lista = self.cursor.execute("SELECT nome_cliente FROM Clientes")
        for i in lista:
            self.clientes_lista_encomendas.insert("", "end", values=i)
            self.clientes_lista_encomendas.update()
        self.desconecta_bd()
    def buscar_produtos(self):#cria um dicionario com os nomes dos produtos para por em radiobuttons para depois escolher
        self.conecta_bd()
        produtos  = self.cursor.execute("SELECT nome_produto FROM Produtos WHERE ativo = 1").fetchall()
        self.desconecta_bd()
        return [produto[0] for produto in produtos]
    def produtos_encomendas(self):
        for widget in self.frame_checkbuttons.winfo_children():#apaga tudo antes de fazer outra vez
            widget.destroy()

        # Variaveis para ajudar no ciclo
        produtos = self.buscar_produtos()
        self.check_var_list = []
        self.quantidade_entries = []

        # for que faz as entrys e os checkbuttons de acordo com o numero de produtos que existe
        for i, produto_nome in enumerate(produtos):
            check_var = IntVar()
            check_button = Checkbutton(self.frame_checkbuttons, text=produto_nome, variable=check_var, bg='#2E3133',fg='white', selectcolor='black')
            check_button.grid(row=i, column=0, sticky="w")
            quantidade_entry = Entry(self.frame_checkbuttons, width=5, bg='#2E3133', fg='white')
            quantidade_entry.insert(0, "0")
            quantidade_entry.grid(row=i, column=1, padx=(5, 0))
            self.quantidade_entries.append(quantidade_entry)
            self.check_var_list.append(check_var)
            self.frame_checkbuttons.update_idletasks()
    def fazer_encomendas(self):
        try: #tenta fazer a encomenda
            quantidades = self.obter_quantidades() #vai buscar as quantidades de todos os produtos
            fritos_congelados = self.obter_checkbox_status() #vai buscar os estados das checkboxs parasaber se é frito ou não

            selecionado = self.clientes_lista_encomendas.selection()[0]
            values = self.clientes_lista_encomendas.item(selecionado).get('values')
            valor_selecionado = values[0] if values else None #saber qual a pessoal é que vamos fazer a encomenda

            if hasattr(self, 'nome_erro_encomendas'):
                self.nome_erro_encomendas.config(text="")

            if not self.ver_combobox(): #ve se as duas comboboxs estao selecionadas
                if not hasattr(self, 'nome_erro_combobox'):
                    self.nome_erro_combobox = Label(self.frameListaClientes, bg="#2E3133",text="As duas Comboboxs têm de estar selecionadas",font=("", 8, "bold"), fg='red')
                    self.nome_erro_combobox.place(x=500, y=5)
                if hasattr(self, 'nome_erro_combobox'):
                    self.nome_erro_combobox.config(text="As duas Comboboxs têm de estar selecionadas")
                raise
            else:
                if hasattr(self, 'nome_erro_combobox'):
                    self.nome_erro_combobox.config(text="")

        except Exception as e:
            if not hasattr(self, 'nome_erro_encomendas'):
                self.nome_erro_encomendas = Label(self.frameListaClientes, bg="#2E3133",text="Nenhum Cliente Selecionado", font=("", 8, "bold"), fg='red')
                self.nome_erro_encomendas.place(x=25, y=60)

        self.conecta_bd()
        try:
            resultado_encomendas = self.cursor.execute("SELECT COUNT(DISTINCT id_Encomendas) FROM Encomendas;").fetchone()
            id_encomenda = resultado_encomendas[0] + 1 #adiciona 1 ao id_encomnda

            self.cursor.execute('SELECT id_clientes FROM Clientes WHERE nome_cliente = ?',(valor_selecionado,))
            id_clientes = self.cursor.fetchone()[0] #vai buscar o id_cliente a partir

            self.desconecta_bd()
            if id_clientes:
                if all(qty == 0 for qty in quantidades) and all(str(qty).isdigit() for qty in quantidades): #se houver algum erro nas quantidades
                    raise Exception

                self.conecta_bd()
                data_encomenda = datetime.now().strftime("%Y-%m-%d")

                metodo_pagamento = 0 if self.Combo_pagamento.current() == 0 else 1 #confirma o metedo de pagamento pela posição da combobox
                metodo_entrega = 0 if self.Combo_levar.current() == 0 else 1 #confirma o metedo de entrega pela posição da combobox

                #faz a encomenda
                self.cursor.execute("INSERT INTO Encomendas (`id_Encomendas`, `id_clientes`, `data_encomenda`, `metedo_pagamento`, `metedo_entrega`) VALUES (?, ?, ?, ?, ?);",(id_encomenda, id_clientes, data_encomenda, metodo_pagamento,metodo_entrega))
                self.desconecta_bd()

                self.conecta_bd()
                for i, quantidade in enumerate(quantidades): #vai adicionar a linha de encomenda para cada produto com quantidades maiior que 0
                    if int(quantidade) > 0:
                        self.cursor.execute("""INSERT INTO Linha_de_Encomenda (Encomendas_id_Encomendas, Produtos_id_produto, congelados, quantidade) VALUES (?, ?, ?, ?);""",(id_encomenda, i + 1, fritos_congelados[i], quantidade))
                        self.conn.commit()
                self.desconecta_bd()

                #Limpa as entrys todas
                self.clientes_lista_encomendas.selection_remove(self.clientes_lista_encomendas.selection())
                if hasattr(self, 'nome_erro_encomendas'):
                    self.nome_erro_encomendas.config(text='')
                if hasattr(self, 'nome_erro_produtos'):
                    self.nome_erro_produtos.config(text="")

                for entry in self.quantidade_entries:
                    entry.delete(0, 'end')
                    entry.insert(0, "0")
                for check_var in self.check_var_list:
                    check_var.set(0)
                self.Combo_pagamento.set('')
                self.Combo_levar.set('')

        except Exception:
            hora_erro = datetime.now().strftime("%H:%M:%S")
            print(f"Ocorreu um erro ao tentar adicionar/atualizar algo na base dados - {hora_erro}")

        self.N_encomendasFrame4_Inicio.config(text=int(self.contar_Encomendas()))
        self.lista_clientes_encomendas()
        self.grafico()
        self.mostrar_encomendas()
        for var in self.check_var_list:
            var.set(0)
        for entry in self.quantidade_entries:
            entry.delete(0, 'end')
            entry.insert(0, "0")
    def ver_combobox(self): #vai ver se as combobox estão selecionadas
        return True if self.Combo_pagamento.get() and self.Combo_levar.get() else False
    def obter_checkbox_status(self):#verifica as checkboxes que estao selecionadas e ver se são fritos ou congelados
        return [1 if check_var.get() == 1 else 0 for check_var in self.check_var_list]
    def obter_quantidades(self):#serve para saber os valores que estavam nas entrys e saber as quantidades (em duzias de cada produto)
        quantidades = []
        erros = []

        if not hasattr(self, 'nome_erro_produtos'):
            self.nome_erro_produtos = Label(self.frameListaClientes, bg="#2E3133", text=" ", font=("", 8, "bold"),fg='red')
            self.nome_erro_produtos.place(x=198, y=60)

        for i, entry in enumerate(self.quantidade_entries):#verifica se todas as entrys sao numeros
            valor = entry.get()
            entry.config(fg='white')
            if valor == '':
                valor = 0
            elif not valor.isdigit():
                erros.append(i)
                entry.config(fg='red')
                quantidades = []
                break
            quantidades.append(int(valor))

        if erros: #Se estiver algum dos erros aparece a mensagem e limpa a lista
            self.nome_erro_produtos.config(text='Erro ao escrever quantidades')
            quantidades = []
        elif all(qty == 0 for qty in quantidades):
            self.nome_erro_produtos.config(text='Todas as quantidades são zero')
        else:
            self.nome_erro_produtos.config(text='')
        return quantidades
    def mostrar_encomendas(self):
        for widget in self.frame_encomendas.winfo_children():#apaga tudo antes de fazer outra vez
            widget.destroy()
        self.conecta_bd()

        # Vai buscar o ID, Cliente e Data de todas as encomendas
        dados_encomendas = self.cursor.execute("SELECT Encomendas.id_Encomendas AS 'ID Encomenda', Clientes.nome_cliente AS 'Nome do Cliente', Encomendas.data_encomenda AS 'Data', CASE WHEN Encomendas.metedo_pagamento = 0 THEN 'Pagamento em Mãos' ELSE 'MBway' END AS 'Método de Pagamento', CASE WHEN Encomendas.metedo_entrega = 0 THEN 'Pickup' ELSE 'Entrega ao domicílio' END AS 'Método de Entrega', Encomendas.mensagem FROM Encomendas JOIN Clientes ON Encomendas.id_clientes = Clientes.id_clientes ORDER BY Encomendas.id_Encomendas DESC;").fetchall()
        self.desconecta_bd()

        # Para cada encomenda vai adicionar as informações todas (produtos,quantidades e estado)
        for linha_encomenda, info_encomenda in enumerate(dados_encomendas):
            id_encomenda, nome_cliente, data_encomenda, metodo_pagamento, metodo_entrega, mensagem = info_encomenda
            produtos_quantidades = []  # tupula para depois por os produtos, quantidades e estado
            total_encomenda = 0  # variavel para o preço total de cada encomenda

            self.conecta_bd()
            # Vai buscar os produtos de cada encomenda
            dados_produtos = self.cursor.execute("SELECT Produtos.nome_produto, Linha_de_Encomenda.quantidade, CASE WHEN Produtos.nome_produto LIKE '%Rissol%' OR Produtos.nome_produto LIKE '%Rissois%' OR Produtos.nome_produto LIKE '%Croquete%' OR Produtos.nome_produto LIKE '%Trouxa%' THEN Linha_de_Encomenda.congelados ELSE '' END AS congelados, Produtos.preco FROM Linha_de_Encomenda JOIN Produtos ON Linha_de_Encomenda.Produtos_id_produto = Produtos.id_produto WHERE Linha_de_Encomenda.Encomendas_id_Encomendas = ?;",(id_encomenda,)).fetchall()
            self.desconecta_bd()

            # Calcula o preço total da encomenda e preenche a tupula com os dados corretos
            for produtos in dados_produtos:  # isto vai repetir por cada produto que existe
                produto, quantidade, congelados, preco = produtos
                produtos_quantidades.append((produto, quantidade, congelados))
                total_encomenda += quantidade * preco

            # Junta tudo numa string
            encomenda_info = "Encomenda: {}\nCliente: {}\nData: {}\nMétodo de Pagamento: {}\nMétodo de Entrega: {}\nMensagem: {}\nProdutos:\n".format(id_encomenda, nome_cliente, data_encomenda,"Pagamento em Mãos" if metodo_pagamento == 'Pagamento em Mãos' else 'MBway',"Pickup" if metodo_entrega == 'Pickup' else 'Entrega ao domicílio',"[Nada]" if mensagem == None else mensagem)

            for produto, quantidade, congelados, preco in dados_produtos:  # vai adicionar à encomenda_info a informação de cada produto
                tipo_congelamento = "Congelado" if congelados == 0 else "Fritos"
                congelados_str = "" if congelados == "" else ", " + tipo_congelamento
                unidade_medida = "duzias" if any(keyword in produto for keyword in ["Rissol", "Rissois", "Croquete", "Trouxa"]) else "unidades"
                encomenda_info += "- {} ({} {}{} , Preço: {:.2f}€)\n".format(produto, quantidade, unidade_medida,congelados_str, preco)
            encomenda_info += "Total da Encomenda: {:.2f}€".format(total_encomenda)

            # Junta tudo e mete numa label
            Encomenda = Label(self.frame_encomendas, text=encomenda_info, font=("", 12), fg='white', bg='#2E3133',justify='left', )
            Encomenda.grid(row=linha_encomenda, column=0, padx=5, pady=5, sticky='w', columnspan=3)

        # Atualizar a frame
        self.canvas_encomendas.update_idletasks()
        self.canvas_encomendas.config(scrollregion=self.canvas_encomendas.bbox("all"))
    #funções para o Lucro
    def AnosServico(self): #mostra umaa combobox com todos os anos onde houve encomendas
        self.conecta_bd()
        anos = self.cursor.execute("SELECT DISTINCT strftime('%Y', data_encomenda) FROM Encomendas").fetchall()
        self.desconecta_bd()
        self.Combo_ano['values'] =  [str(ano[0]) for ano in anos]
    def Lucro_ano(self):#Função que dependendo do ano vai mostrando o lucro de todos os meses
        self.lista_lucro.delete(*self.lista_lucro.get_children())

        try:
            self.conecta_bd()
            ano = self.Combo_ano.get() #vai buscar o ano da combobox
            nomes_meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro','Outubro', 'Novembro', 'Dezembro'] #variavel com os meses para passar o numero do mes para o nome respetivo dele
            resultado = self.cursor.execute("SELECT strftime('%m', data_encomenda) AS mes, SUM(preco_produto * quantidade) AS total_lucro FROM Encomendas JOIN Linha_de_Encomenda ON Encomendas.id_Encomendas = Linha_de_Encomenda.Encomendas_id_Encomendas JOIN Produtos ON Linha_de_Encomenda.Produtos_id_produto = Produtos.id_produto WHERE strftime('%Y', data_encomenda) = ? GROUP BY mes ORDER BY mes;",(ano,))
            for linha in resultado:
                nome_mes = nomes_meses[int(linha[0]) - 1]
                linha = (nome_mes, str(linha[1]) + " €")
                self.lista_lucro.insert("", "end", values=linha)

            #Vai fazer as informações necessarias do ano escolhido
            if not hasattr(self, 'stats_frame_ano'): #Frame onde vai estar tudo
                self.stats_frame_ano = Frame(self.frameLucro_stats, bg="#2E3133", bd=1, highlightbackground="white",highlightthickness=1)
                self.stats_frame_ano.place(x=275, y=70, width=700, height=255)

            #Label a dizer o ano selecionado
            self.ano_label = Label(self.stats_frame_ano, text="Lucro e Estatisticas de " + ano, bg='#2E3133', font=("", 15, "bold"),fg='white')
            self.ano_label.place(x=200, y=0)

            #Label a dizer melhores compradores
            self.melhores_compradores_label = Label(self.stats_frame_ano, text="Melhores Compradores ", bg='#2E3133', font=("", 12, "bold"),fg='white')
            self.melhores_compradores_label.place(x=20, y=30)

            #Treeview dos melhores compradores do ano em questão
            self.lista_melhores_clientes_ano = tkinter.ttk.Treeview(self.stats_frame_ano, columns=("col1", "col2"))
            self.lista_melhores_clientes_ano.heading("#0", text="")
            self.lista_melhores_clientes_ano.heading("#1", text="Cliente", anchor='w')
            self.lista_melhores_clientes_ano.heading("#2", text="Total Encomendas", anchor='w')
            self.lista_melhores_clientes_ano.column("#0", width=1, stretch=NO)
            self.lista_melhores_clientes_ano.column("#1", width=73, stretch=NO)
            self.lista_melhores_clientes_ano.column("#2", width=110, stretch=NO)
            self.lista_melhores_clientes_ano.place(relx=0.03, rely=0.25, relwidth=0.27, relheight=0.70)
            self.melhores_clientes_ano(ano)

            #Scrollbar
            self.sroll = Scrollbar(self.lista_melhores_clientes_ano, orient="vertical")
            self.sroll.configure(command=self.lista_melhores_clientes_ano.yview)
            self.sroll.place(relx=0.89, rely=0.145, relwidth=0.1, relheight=0.85)
            self.lista_melhores_clientes_ano.configure(yscroll=self.sroll.set)

            #Label a dizer Produtos mais Comprados
            self.produtos_mais_comprados_label = Label(self.stats_frame_ano, text="Produtos mais Comprados", bg='#2E3133',font=("", 12, "bold"), fg='white')
            self.produtos_mais_comprados_label.place(x=225, y=30)

            #Treeview dos melhores produtos do ano em questão
            self.lista_melhores_prod_ano = tkinter.ttk.Treeview(self.stats_frame_ano, columns=("col1", "col2"))
            self.lista_melhores_prod_ano.heading("#0", text="")
            self.lista_melhores_prod_ano.heading("#1", text="Produto", anchor='w')
            self.lista_melhores_prod_ano.heading("#2", text="Total Encomendados", anchor='w')
            self.lista_melhores_prod_ano.column("#0", width=1, stretch=NO)
            self.lista_melhores_prod_ano.column("#1", width=80, stretch=NO)
            self.lista_melhores_prod_ano.column("#2", width=124, stretch=NO)
            self.lista_melhores_prod_ano.place(relx=0.329, rely=0.25, relwidth=0.3, relheight=0.70)
            self.melhores_prod_ano(ano)

            # Scrollbar
            self.sroll = Scrollbar(self.lista_melhores_prod_ano, orient="vertical")
            self.sroll.configure(command=self.lista_melhores_prod_ano.yview)
            self.sroll.place(relx=0.89, rely=0.145, relwidth=0.1, relheight=0.85)
            self.lista_melhores_prod_ano.configure(yscroll=self.sroll.set)

            #Label a dizer Total Faturado
            self.total_faturado_label = Label(self.stats_frame_ano, text="Total Faturado", bg='#2E3133',font=("", 12, "bold"), fg='white')
            self.total_faturado_label.place(x=500, y=30)

            #Treeview dos melhores produtos do ano em questão
            self.lista_total_faturado_ano = tkinter.ttk.Treeview(self.stats_frame_ano, columns=("col1"))
            self.lista_total_faturado_ano.heading("#0", text="")
            self.lista_total_faturado_ano.heading("#1", text="Lucro Total", anchor='center')
            self.lista_total_faturado_ano.column("#0", width=1, stretch=NO)
            self.lista_total_faturado_ano.column("#1", width=204, stretch=NO, anchor='center')
            self.lista_total_faturado_ano.place(relx=0.66, rely=0.25, relwidth=0.3, relheight=0.70)
            self.lucro_total_ano(ano)

        except:
            print("Ano ou Mês ainda não selecionado")
    def Lucro_mes(self):#Dependedo de cada mes selecionado vai mostar as estatisticas e informações uteis
        try:
            ano = self.Combo_ano.get() # O ano que esta na combobox
            selecionado = self.lista_lucro.selection()
            mes = self.lista_lucro.item(selecionado[0], "values")[0] #saber o nome do mes que esta selecionado
            nomes_meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro','Outubro', 'Novembro','Dezembro']  # variavel com os meses para passar o numero do mes para o nome respetivo dele
            num_mes = str(nomes_meses.index(mes) + 1).zfill(2) #vai usar o array para passar para numeros e garantir que vai ter dois digitos

            if not hasattr(self, 'stats_frame_mes'):  # Frame onde vai estar tudo
                self.stats_frame_mes =Frame(self.frameLucro_stats, bg="#2E3133", bd=1, highlightbackground="white",highlightthickness=1)
                self.stats_frame_mes.place(x=275, y=350, width=700, height=275)

            # Label a dizer o ano e mes selecionado
            if not hasattr(self, 'ano_mes_label'):
                self.ano_mes_label = Label(self.stats_frame_mes, text="Lucro e Estatisticas de " +mes+ " de " + ano, bg='#2E3133',font=("", 15, "bold"), fg='white')
                self.ano_mes_label.place(x=200, y=0)
            else:
                self.ano_mes_label.config(text="Lucro e Estatisticas de " +mes+ " de " + ano)

            # Label a dizer melhores compradores
            self.melhores_compradores_label_mes = Label(self.stats_frame_mes, text="Melhores Compradores ", bg='#2E3133',font=("", 12, "bold"), fg='white')
            self.melhores_compradores_label_mes.place(x=20, y=30)

            # Treeview dos melhores compradores do ano e mes em questão
            self.lista_melhores_clientes_mes = tkinter.ttk.Treeview(self.stats_frame_mes, columns=("col1", "col2"))
            self.lista_melhores_clientes_mes.heading("#0", text="")
            self.lista_melhores_clientes_mes.heading("#1", text="Cliente", anchor='w')
            self.lista_melhores_clientes_mes.heading("#2", text="Total Encomendas", anchor='w')
            self.lista_melhores_clientes_mes.column("#0", width=1, stretch=NO)
            self.lista_melhores_clientes_mes.column("#1", width=73, stretch=NO)
            self.lista_melhores_clientes_mes.column("#2", width=110, stretch=NO)
            self.lista_melhores_clientes_mes.place(relx=0.03, rely=0.25, relwidth=0.27, relheight=0.70)
            self.melhores_clientes_mes(ano,num_mes)

            # Scrollbar
            self.sroll = Scrollbar(self.lista_melhores_clientes_mes, orient="vertical")
            self.sroll.configure(command=self.lista_melhores_clientes_mes.yview)
            self.sroll.place(relx=0.89, rely=0.145, relwidth=0.1, relheight=0.85)
            self.lista_melhores_clientes_mes.configure(yscroll=self.sroll.set)

            # Label a dizer Produtos mais Comprados
            self.produtos_mais_comprados_label_mes = Label(self.stats_frame_mes, text="Produtos mais Comprados",bg='#2E3133', font=("", 12, "bold"), fg='white')
            self.produtos_mais_comprados_label_mes.place(x=225, y=30)

            # Treeview dos melhores produtos do ano e mes em questão
            self.lista_melhores_prod_mes = tkinter.ttk.Treeview(self.stats_frame_mes, columns=("col1", "col2"))
            self.lista_melhores_prod_mes.heading("#0", text="")
            self.lista_melhores_prod_mes.heading("#1", text="Produto", anchor='w')
            self.lista_melhores_prod_mes.heading("#2", text="Total Encomendados", anchor='w')
            self.lista_melhores_prod_mes.column("#0", width=1, stretch=NO)
            self.lista_melhores_prod_mes.column("#1", width=80, stretch=NO)
            self.lista_melhores_prod_mes.column("#2", width=124, stretch=NO)
            self.lista_melhores_prod_mes.place(relx=0.329, rely=0.25, relwidth=0.3, relheight=0.70)
            self.melhores_prod_mes(ano,num_mes)

            # Scrollbar
            self.sroll = Scrollbar(self.lista_melhores_prod_mes, orient="vertical")
            self.sroll.configure(command=self.lista_melhores_prod_mes.yview)
            self.sroll.place(relx=0.89, rely=0.145, relwidth=0.1, relheight=0.85)
            self.lista_melhores_prod_mes.configure(yscroll=self.sroll.set)

            # Label a dizer Total Faturado
            self.total_faturado_label_mes = Label(self.stats_frame_mes, text="Total Faturado", bg='#2E3133',font=("", 12, "bold"), fg='white')
            self.total_faturado_label_mes.place(x=500, y=30)

            # Treeview dos melhores produtos do ano e mes em questão
            self.lista_total_faturado_mes = tkinter.ttk.Treeview(self.stats_frame_mes, columns=("col1"))
            self.lista_total_faturado_mes.heading("#0", text="")
            self.lista_total_faturado_mes.heading("#1", text="Lucro Total", anchor='center')
            self.lista_total_faturado_mes.column("#0", width=1, stretch=NO)
            self.lista_total_faturado_mes.column("#1", width=204, stretch=NO, anchor='center')
            self.lista_total_faturado_mes.place(relx=0.66, rely=0.25, relwidth=0.3, relheight=0.70)
            self.lucro_total_mes(ano,num_mes)
        except:
            print("Ano ou mês ainda não selecionado")

    ##Estas 3 funções são para mostrar as estatisticas e lucro do ano que foi escolhido
    def melhores_clientes_ano(self, ano):#função que mostra as pessoas que fizeram mais encomendas num certo ano
        self.lista_melhores_clientes_ano.delete(*self.lista_melhores_clientes_ano.get_children())
        self.conecta_bd()
        lista = self.cursor.execute("SELECT c.nome_cliente, COUNT(e.id_Encomendas) AS total_encomendas FROM Clientes c JOIN Encomendas e ON c.id_clientes = e.id_clientes WHERE strftime('%Y', e.data_encomenda) = ? GROUP BY c.id_clientes ORDER BY total_encomendas DESC;",(ano,))
        for i in lista:
            self.lista_melhores_clientes_ano.insert("", "end", values=i)
        self.lista_melhores_clientes_ano.update()
        self.desconecta_bd()
    def melhores_prod_ano(self, ano):#função que mostra os produtos que foram mais encomendados num certo ano
        self.lista_melhores_prod_ano.delete(*self.lista_melhores_prod_ano.get_children())
        self.conecta_bd()
        lista = self.cursor.execute("SELECT p.nome_produto, SUM(le.quantidade) AS total_quantidade FROM Produtos p JOIN Linha_de_Encomenda le ON p.id_produto = le.Produtos_id_produto JOIN Encomendas e ON le.Encomendas_id_Encomendas = e.id_Encomendas WHERE strftime('%Y', e.data_encomenda) = ? GROUP BY p.id_produto ORDER BY total_quantidade DESC;",(ano,))
        for i in lista:
            self.lista_melhores_prod_ano.insert("", "end", values=i)
        self.lista_melhores_prod_ano.update()
        self.desconecta_bd()
    def lucro_total_ano(self, ano):#função que mostra o lucro total de um certo ano
        self.lista_total_faturado_ano.delete(*self.lista_total_faturado_ano.get_children())
        self.conecta_bd()
        lista = self.cursor.execute(
            "SELECT SUM(le.quantidade * le.preco_produto) || ' €' AS lucro_total FROM Linha_de_Encomenda le JOIN Encomendas e ON le.Encomendas_id_Encomendas = e.id_Encomendas WHERE strftime('%Y', e.data_encomenda) = ?;",
            (ano,))
        for i in lista:
            self.lista_total_faturado_ano.insert("", "end", values=i)
        self.lista_total_faturado_ano.update()
        self.desconecta_bd()
    ##Estas 3 funções são para mostrar as estatisticas e lucro do mes e do que foi escolhido
    def melhores_clientes_mes(self,ano,mes):#função que mostra as pessoas que fizeram mais encomendas num certo mes de um certo ano
        self.lista_melhores_clientes_mes.delete(*self.lista_melhores_clientes_mes.get_children())
        self.conecta_bd()
        lista = self.cursor.execute(
            "SELECT c.nome_cliente, COUNT(e.id_Encomendas) AS total_encomendas FROM Clientes c JOIN Encomendas e ON c.id_clientes = e.id_clientes WHERE strftime('%Y', e.data_encomenda) = ? AND strftime('%m', e.data_encomenda) = ? GROUP BY c.id_clientes ORDER BY total_encomendas DESC;",
            (ano,mes,))
        for i in lista:
            self.lista_melhores_clientes_mes.insert("", "end", values=i)
        self.lista_melhores_clientes_mes.update()
        self.desconecta_bd()
    def melhores_prod_mes(self,ano,mes):#função que mostra os produtos que foram mais encomendados num certo mes e de um certo ano
        self.lista_melhores_prod_mes.delete(*self.lista_melhores_prod_mes.get_children())
        self.conecta_bd()
        lista = self.cursor.execute("SELECT p.nome_produto, SUM(le.quantidade) AS total_quantidade FROM Produtos p JOIN Linha_de_Encomenda le ON p.id_produto = le.Produtos_id_produto JOIN Encomendas e ON le.Encomendas_id_Encomendas = e.id_Encomendas WHERE strftime('%Y', e.data_encomenda) = ? AND strftime('%m', e.data_encomenda) = ? GROUP BY p.id_produto ORDER BY total_quantidade DESC;",
                                    (ano,mes,))
        for i in lista:
            self.lista_melhores_prod_mes.insert("", "end", values=i)
        self.lista_melhores_prod_mes.update()
        self.desconecta_bd()
    def lucro_total_mes(self,ano,mes):#função que mostra o lucro total de um certo mes de um certo ano
        self.lista_total_faturado_mes.delete(*self.lista_total_faturado_mes.get_children())
        self.conecta_bd()
        lista = self.cursor.execute(
            "SELECT SUM(le.quantidade * le.preco_produto) || ' €' AS lucro_total FROM Linha_de_Encomenda le JOIN Encomendas e ON le.Encomendas_id_Encomendas = e.id_Encomendas WHERE strftime('%Y', e.data_encomenda) = ? AND strftime('%m', e.data_encomenda) = ?;",
            (ano,mes,))
        for i in lista:
            self.lista_total_faturado_mes.insert("", "end", values=i)
        self.lista_total_faturado_mes.update()
        self.desconecta_bd()
        #mudar as treeviews disto tudo para as certas

    #funções para as mensagens
    def mostrar_mensagens(self):
        for widget in self.frame_mensagens.winfo_children(): #apaga tudo antes de fazer outra vez
            widget.destroy()
        self.conecta_bd()

        # Vai buscar todas as mensagens dos clientes
        dados_mensagens = self.cursor.execute("SELECT Mensagens_Clientes.id_mensagem, Clientes.nome_cliente, Mensagens_Clientes.mensagem, Clientes.imagem_perfil FROM Mensagens_Clientes JOIN Clientes ON Mensagens_Clientes.id_cliente = Clientes.id_clientes;").fetchall()
        self.desconecta_bd()

        # Para cada mensagem, adiciona as informações (id da mensagem, nome do cliente e a mensagem)
        for linha_mensagem, info_mensagem in enumerate(dados_mensagens):
            id_mensagem, nome_cliente, mensagem, imagem_perfil = info_mensagem

            # Adiciona a imagem de perfil do cliente, se existir
            if imagem_perfil:
                imagem = PhotoImage(file=imagem_perfil).subsample(10)  # Substitua 4 pelo fator de redução desejado
                pfp_imagem = Label(self.frame_mensagens, image=imagem, bg='#2E3133')
                pfp_imagem.image = imagem
                pfp_imagem.grid(row=linha_mensagem, column=0, padx=5, pady=5, sticky='e')

            # Cria uma label com as informações da mensagem
            mensagem_info = "Mensagem Nº: {}\nCliente: {} \nMensagem: {}" .format(id_mensagem, nome_cliente,mensagem)
            label_mensagem = Label(self.frame_mensagens, text=mensagem_info, font=("", 12), fg='white', bg='#2E3133',justify='left')
            label_mensagem.grid(row=linha_mensagem, column=1, padx=5, pady=5, sticky='w')

        # Atualiza a frame
        self.frame_mensagens.update_idletasks()
        self.canvas_mensagens.config(scrollregion=self.canvas_mensagens.bbox("all"))
class Dashboard(Funcs):
    def __init__(self, window):
        # janela principal
        self.window = window
        self.window.title("Rissois")
        self.window.geometry("1366x768")
        self.window.state("zoomed")
        self.window.configure(background="#17191F")

        # Icon da app
        myappid = 'mycompany.myproduct.subproduct.version'
        ctypes.windll.shell32.SetCurrentProcessExplicitAppUserModelID(myappid)
        self.window.iconbitmap(r'../img/favicon.ico')

        # Decorar a Treeview
        style = ttk.Style()
        style.theme_use('clam')
        style.map('Treeview',background=[('selected', '#FD9C3A'), ('!selected', '#2E3133')],foreground=[('selected', 'black'), ('!selected', 'white')],)
        style.map('Treeview.Heading',foreground=[('selected', 'white')])
        style.configure('Treeview.Heading', background="#FD9C3A")
        style.configure('Treeview',rowheight=35)
        style.configure('Treeview', fieldbackground='#2E3133')

        #Decorar a Combobox
        style.map('TCombobox', fieldbackground=[('readonly', '#FD9C3A'), ('focus', '#FD9C3A')])
        style.configure('TCombobox', background="#2E3133", foreground='black', font=("", 10, "bold"),bordercolor='white')

        ##############################################################################################################################################
        ##################################################### Barra Lateral ##########################################################################
        ##############################################################################################################################################

        # Barra Lateral
        self.sidebar = Frame(self.window, bg="#2E3133")
        self.sidebar.place(x=0, y=0, width=300, height=1900)

        ## Empresa Imagem
        EmpresaLogo = ImageTk.PhotoImage(Image.open('../img/profile.png'))
        self.prety_icon_empresa_sidebar = Label(self.sidebar, image=EmpresaLogo, bg='#FD9C3A')
        self.prety_icon_empresa_sidebar.image = EmpresaLogo
        self.prety_icon_empresa_sidebar.place(x=70, y=80)

        ## Empresa Nome
        self.EmpresaNome = Label(self.sidebar, text='Claúdia e Filhos, Lda', bg='#2E3133', font=("", 15, "bold"),fg='white')
        self.EmpresaNome.place(x=45, y=240)

        # Opções
        ## Btn Inicio
        self.dashboard_text = Button(self.sidebar, text='Inicio', bg='#2E3133', font=("", 12, "bold"), fg='white',cursor='hand2', activebackground='#FD9C3A', bd=5, width=10,command=lambda: self.frameInicio.lift())
        self.dashboard_text.place(x=30, y=325)

        ## Btn Clientes
        self.manager_text = Button(self.sidebar, text='Clientes', bg='#2E3133', font=("", 12, "bold"), fg='white',cursor='hand2', activebackground='#FD9C3A', bd=5, width=10, command=lambda: self.frameClientes.lift())
        self.manager_text.place(x=160, y=325)

        ## Btn Produtos
        self.settings_text = Button(self.sidebar, text='Produtos', bg='#2E3133', font=("", 12, "bold"), fg='white',cursor='hand2', activebackground='#FD9C3A', bd=5, width=10,command=lambda: self.frameProdutos.lift())
        self.settings_text.place(x=30, y=375)

        ## Btn Encomendas
        self.manager_text = Button(self.sidebar, text='Encomendas', bg='#2E3133', font=("", 12, "bold"), fg='white',cursor='hand2', activebackground='#FD9C3A', bd=5, width=10,command=lambda: self.frameEncomendas.lift())
        self.manager_text.place(x=160, y=375)

        ## Btn Lucro
        self.settings_text = Button(self.sidebar, text='Lucro', bg='#2E3133', font=("", 12, "bold"), fg='white',cursor='hand2', activebackground='#FD9C3A', bd=5, width=10,command=lambda: self.frameLucro.lift())
        self.settings_text.place(x=30, y=425)

        ## Btn Mensagens
        self.exit_text = Button(self.sidebar, text='Mensagens', bg='#2E3133', font=("", 12, "bold"), fg='white',cursor='hand2', activebackground='#FD9C3A', bd=5, width=10,command=lambda: self.frameMensagens.lift())
        self.exit_text.place(x=160, y=425)

        ##############################################################################################################################################
        ##################################################### FRAME Inicio ##########################################################################
        ##############################################################################################################################################

        #Frame Inicio (O primeiro de quando se abre o programa)
        self.frameInicio = Frame(self.window, bg="#17191F")
        self.frameInicio.place(x=300, y=0, width=1366, height=768)

        ## Label Dashboard
        self.heading_Inicio = Label(self.frameInicio, text="Dashboard", font=("", 13, "bold"), fg='white', bg='#17191F')
        self.heading_Inicio.place(x=25, y=50)

        ##Linha
        self.line_Inicio = Label(self.frameInicio, text="____________", font=("", 10, "bold"), fg='#FD9C3A', bg='#17191F')
        self.line_Inicio.place(x=25, y=25)

        ##Icon de aviso para dizer oq a pagina faz (Pagina do Inicio)
        AvisoImagem = ImageTk.PhotoImage(Image.open('../img/aviso.png'))
        self.prety_icon_aviso_Inicio = Label(self.frameInicio, image=AvisoImagem, bg='#17191F')
        self.prety_icon_aviso_Inicio.image = AvisoImagem
        self.prety_icon_aviso_Inicio.place(x=120, y=45)
        self.prety_icon_aviso_Inicio.bind("<Button-1>",lambda event: messagebox.showinfo("Aviso!", "Esta é a pagina inicial, apenas tem o importante. Para mais detalhes use os botões ao lado para ver as outras janelas."))

        ## Frame 1 do body (Grafico)
        self.bodyFrame1_Inicio = Frame(self.frameInicio, bg="#2E3133")
        self.bodyFrame1_Inicio.place(x=28.5, y=90, width=1011, height=350)
        self.grafico()

        ## Frame 2 do body (Clientes)
        self.bodyFrame2_Inicio = Frame(self.frameInicio, bg="#2E3133",cursor='hand2')
        self.bodyFrame2_Inicio.place(x=28, y=475, width=310, height=220)
        self.bodyFrame2_Inicio.bind("<Button-1>", lambda event: self.frameClientes.lift())

        ### Label a dizer Clientes
        self.labelFrame2_Inicio = Label(self.bodyFrame2_Inicio, bg="#2E3133", text="Clientes", font=("", 15, "bold"), fg='white')
        self.labelFrame2_Inicio.place(x=60, y=25)

        ### Linha
        self.lineFrame2_Inicio = Label(self.bodyFrame2_Inicio, text="_________", font=("", 10, "bold"), fg='#FD9C3A',bg='#2E3133')
        self.lineFrame2_Inicio.place(x=60, y=5)

        ### Imagem
        ClientesImage = ImageTk.PhotoImage(Image.open('../img/clientes.png'))
        self.prety_icon_clientes_Inicio = Label(self.bodyFrame2_Inicio, image=ClientesImage, bg='#2E3133')
        self.prety_icon_clientes_Inicio.image = ClientesImage
        self.prety_icon_clientes_Inicio.place(x=25, y=21)

        ###Numero de CLientes
        self.N_clientesFrame2_Inicio = Label(self.bodyFrame2_Inicio, bg="#2E3133", text=int(self.contar_clientes()), font=("", 50, "bold"), fg='white')
        self.N_clientesFrame2_Inicio.place(x=115, y=70)

        ## Frame 3 do body (Produtos)
        self.bodyFrame3_Inicio = Frame(self.frameInicio, bg="#2E3133", cursor='hand2')
        self.bodyFrame3_Inicio.place(x=380, y=475, width=310, height=220)
        self.bodyFrame3_Inicio.bind("<Button-1>", lambda event: self.frameProdutos.lift())


        ### Label a dizer Produtos
        self.labelFrame3_Inicio = Label(self.bodyFrame3_Inicio, bg="#2E3133", text="Produtos", font=("", 15, "bold"), fg='white')
        self.labelFrame3_Inicio.place(x=60, y=25)

        ### Linha
        self.lineFrame3_Inicio = Label(self.bodyFrame3_Inicio, text="_________", font=("", 10, "bold"), fg='#FD9C3A',bg='#2E3133')
        self.lineFrame3_Inicio.place(x=60, y=5)

        ### Imagem
        ProdutosImage = ImageTk.PhotoImage(Image.open('../img/shopping-cart.png'))
        self.prety_icon_produtos_Inicio = Label(self.bodyFrame3_Inicio, image=ProdutosImage, bg='#2E3133')
        self.prety_icon_produtos_Inicio.image = ProdutosImage
        self.prety_icon_produtos_Inicio.place(x=25, y=21)

        ###Numero de Produtos
        self.N_produtosFrame3_Inicio = Label(self.bodyFrame3_Inicio, bg="#2E3133", text=str(self.contar_Produtos()), font=("", 50, "bold"), fg='white')
        self.N_produtosFrame3_Inicio.place(x=115, y=70)

        ## Frame 4 do body (Encomendas)
        self.bodyFrame4_Inicio = Frame(self.frameInicio, bg="#2E3133", cursor='hand2')
        self.bodyFrame4_Inicio.place(x=730, y=475, width=310, height=220)
        self.bodyFrame4_Inicio.bind("<Button-1>", lambda event: self.frameEncomendas.lift())

        ### Label a dizer Encomendas
        self.labelFrame4_Inicio = Label(self.bodyFrame4_Inicio, bg="#2E3133", text="Encomendas", font=("", 15, "bold"), fg='white')
        self.labelFrame4_Inicio.place(x=60, y=25)

        ### Linha
        self.lineFrame4_Inicio = Label(self.bodyFrame4_Inicio, text="_______________", font=("", 10, "bold"), fg='#FD9C3A',bg='#2E3133')
        self.lineFrame4_Inicio.place(x=60, y=5)

        ### Imagem
        EncomendasImage = ImageTk.PhotoImage(Image.open('../img/encomenda.png'))
        self.prety_icon_encomendas_Inicio = Label(self.bodyFrame4_Inicio, image=EncomendasImage, bg='#2E3133')
        self.prety_icon_encomendas_Inicio.image = EncomendasImage
        self.prety_icon_encomendas_Inicio.place(x=25, y=21)

        ###Numero de Encomendas
        self.N_encomendasFrame4_Inicio = Label(self.bodyFrame4_Inicio, bg="#2E3133", text=int(self.contar_Encomendas()), font=("", 50, "bold"),fg='white')
        self.N_encomendasFrame4_Inicio.place(x=115, y=70)


        ##############################################################################################################################################
        ##################################################### FRAME Produtos #########################################################################
        ##############################################################################################################################################

        ##Frame Produtos
        self.frameProdutos = Frame(self.window, bg="#17191F")
        self.frameProdutos.place(x=300, y=0, width=1366, height=768)

        # Corpo
        ## Label Dashboard
        self.heading_Produtos = Label(self.frameProdutos, text="Produtos", font=("", 13, "bold"), fg='white', bg='#17191F')
        self.heading_Produtos.place(x=25, y=50)

        self.line_Produtos = Label(self.frameProdutos, text="____________", font=("", 10, "bold"), fg='#FD9C3A', bg='#17191F')
        self.line_Produtos.place(x=25, y=25)

        ##Icon de aviso para dizer oq a pagina faz (Pagina Produtos)
        self.prety_icon_aviso_Produtos = Label(self.frameProdutos, image=AvisoImagem, bg='#17191F')
        self.prety_icon_aviso_Produtos.image = AvisoImagem
        self.prety_icon_aviso_Produtos.place(x=110, y=45)
        self.prety_icon_aviso_Produtos.bind("<Button-1>",lambda event: messagebox.showinfo("Aviso!", "Esta é a pagina dos Produtos, aqui pode editar, adicionar, remover (ativar/desativar) os produtos que quiser."))

        ## Frame 1 da listagem de Produtos
        self.bodyFrame1_Produtos = Frame(self.frameProdutos, bg="#2E3133")
        self.bodyFrame1_Produtos.place(x=28, y=90, width=660, height=650)

        ### Treeview que mostra todos os Produtos na base de dados
        self.produtos_lista =tkinter.ttk.Treeview(self.bodyFrame1_Produtos, columns=("col1", "col2", "col3","col4","col5","col6"))
        self.produtos_lista.heading("#0", text="")
        self.produtos_lista.heading("#1", text="Produto", anchor='w')
        self.produtos_lista.heading("#2", text="Nome", anchor='w')
        self.produtos_lista.heading("#3", text="Preço", anchor='w')
        self.produtos_lista.heading("#4", text="Descrição", anchor='w')
        self.produtos_lista.heading("#5", text="Imagem", anchor='w')
        self.produtos_lista.heading("#6", text="Ativo", anchor='w')
        self.produtos_lista.column("#0", width=5, stretch=NO)
        self.produtos_lista.column("#1", width=75, stretch=NO)
        self.produtos_lista.column("#2", width=130, stretch=NO)
        self.produtos_lista.column("#3", width=50, stretch=NO)
        self.produtos_lista.column("#4", width=150, stretch=NO)
        self.produtos_lista.column("#5", width=175, stretch=NO)
        self.produtos_lista.column("#6", width=73, stretch=NO)
        self.produtos_lista.place(relx=-0.009, rely=0, relwidth=1.001, relheight=1.05)
        self.produtos_lista.bind("<Double-1>", self.on_double_click)
        self.produtos_lista.bind("<Button-3>", self.on_right_click)

        ### Sroll Bar
        self.sroll = Scrollbar(self.bodyFrame1_Produtos, orient="vertical")
        self.sroll.configure(command=self.produtos_lista.yview)
        self.sroll.place(relx=0.94, rely=0.04, relwidth=0.051, relheight=0.96)
        self.produtos_lista.configure(yscroll=self.sroll.set)
        self.lista_produtos()

        ## Frame 2 (Quantidade de Produtos)
        self.bodyFrame3_Produtos = Frame(self.frameProdutos, bg="#2E3133")
        self.bodyFrame3_Produtos.place(x=730, y=90, width=310, height=150)

        ### Label a dizer Produtos
        self.labelFrame3_Produtos = Label(self.bodyFrame3_Produtos, bg="#2E3133", text="Total de Produtos:", font=("", 15, "bold"),fg='white')
        self.labelFrame3_Produtos.place(x=60, y=25)

        ### Linha
        self.lineFrame3_Produtos = Label(self.bodyFrame3_Produtos, text="________________________", font=("", 10, "bold"), fg='#FD9C3A',bg='#2E3133')
        self.lineFrame3_Produtos.place(x=60, y=0)

        ### Imagem de um carrinho (para estetica)
        ProdutosImage = ImageTk.PhotoImage(Image.open('../img/shopping-cart.png'))
        self.prety_icon_encomendas_produtos = Label(self.bodyFrame3_Produtos, image=ProdutosImage, bg='#2E3133')
        self.prety_icon_encomendas_produtos.image = ProdutosImage
        self.prety_icon_encomendas_produtos.place(x=20, y=16)

        ###Numero de Produtos
        self.N_produtosFrame3_Produtos = Label(self.bodyFrame3_Produtos, bg="#2E3133", text=int( self.contar_Produtos()),font=("", 50, "bold"), fg='white')
        self.N_produtosFrame3_Produtos.place(x=115, y=65)

        ## Frame 4 (Adicionar/Alteral/Remover Produto)
        self.bodyFrame4_Produtos = Frame(self.frameProdutos, bg="#2E3133")
        self.bodyFrame4_Produtos.place(x=730, y=285, width=310 , height=455)

        ### Label a dizer Alterar Produtos
        self.LabelAlterarProduto = Label(self.bodyFrame4_Produtos, bg="#2E3133", text="Alteral Produtos", font=("", 15, "bold"),fg='white')
        self.LabelAlterarProduto.place(x=25, y=25)

        ### Linha
        self.lineFrame4_Produtos = Label(self.bodyFrame4_Produtos, text="______________________",font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.lineFrame4_Produtos.place(x=25, y=0)

        ###Icon de aviso para explicar como alterar/adicionar/remover
        self.prety_icon_aviso_alt_Produtos = Label(self.bodyFrame4_Produtos, image=AvisoImagem, bg='#2E3133')
        self.prety_icon_aviso_alt_Produtos.image = AvisoImagem
        self.prety_icon_aviso_alt_Produtos.place(x=190, y=25)
        self.prety_icon_aviso_alt_Produtos.bind("<Button-1>",lambda event: messagebox.showinfo("Aviso!", "Como usar a janela de alterar produtos:\n\n1)Para alterar um produto dê um click no produto que quer, depois se quiser auto preencher as entrys para saber que produto selecionou faça double click;\n2) Para adicionar um produto não pode ter nenhum produto selecionado;\n3) Para remover faça um click com o botão direito do rato;"))

        ### Imagen de um certo para aceitar
        CertoImage = ImageTk.PhotoImage(Image.open('../img/aceitar.png'))
        self.aceitar = Label(self.bodyFrame4_Produtos, image=CertoImage, bg='#2E3133')
        self.aceitar.image = CertoImage
        self.aceitar.place(x=260, y=25)
        self.aceitar.bind("<Button-1>", lambda event: self.adicionar_produto())

        ### Imagen de uma vassoura para limpar as entrys
        VassouraImage = ImageTk.PhotoImage(Image.open('../img/limpar.png'))
        self.limpar = Label(self.bodyFrame4_Produtos, image=VassouraImage, bg='#2E3133')
        self.limpar.image = VassouraImage
        self.limpar.place(x=225, y=25)
        self.limpar.bind("<Button-1>", lambda event: self.Limpar())

        ### Nome do Produto
        self.nomeProduto = Label(self.bodyFrame4_Produtos, text="Nome do Produto ", font=("", 10, "bold"), fg='white', bg='#2E3133')
        self.nomeProduto.place(x=15, y=75)
        self.labelProduto = Label(self.bodyFrame4_Produtos, text=": ", font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.labelProduto.place(x=130, y=75)
        self.Textbox_Produtos = Entry(self.bodyFrame4_Produtos,bg='#2E3133',fg='white', width=45)
        self.Textbox_Produtos.place(x=140, y=75,width=100 , height=25)

        ### Preço
        self.Preço_Produto = Label(self.bodyFrame4_Produtos, text="Preço ", font=("", 10, "bold"), fg='white', bg='#2E3133')
        self.Preço_Produto.place(x=15, y=105)
        self.LabelPreço = Label(self.bodyFrame4_Produtos, text=": ", font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.LabelPreço.place(x=57, y=105)
        self.Textbox_Preco = Entry(self.bodyFrame4_Produtos,bg='#2E3133',fg='white')
        self.Textbox_Preco.place(x=70, y=105,width=45 , height=25)
        self.LabelEuro = Label(self.bodyFrame4_Produtos, text="€ ", font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.LabelEuro.place(x=117,y=105)

        ###Descrição
        self.LabelDescrição = Label(self.bodyFrame4_Produtos, text="Descrição ", font=("", 10, "bold"), fg='white', bg='#2E3133')
        self.LabelDescrição.place(x=15,y=137)
        self.LabelDescriçãoPontos = Label(self.bodyFrame4_Produtos, text=": ", font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.LabelDescriçãoPontos.place(x=80,y=137)
        self.TextBox_Descrição = Text(self.bodyFrame4_Produtos, bg='#2E3133', fg='white')
        self.TextBox_Descrição.place(x=90, y=135, width=150, height=100)

        ### Produto
        self.LabelImagem= Label(self.bodyFrame4_Produtos,text="Imagem ", font=("", 10, "bold"), fg='white', bg='#2E3133')
        self.LabelImagem.place(x=15,y=240)
        self.LabelImagemPontos = Label(self.bodyFrame4_Produtos,text=": ", font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.LabelImagemPontos.place(x=70,y=240)

        ### Botão para Adicionar Imagem / Alterar Imagem
        self.nova_imagem = Button(self.bodyFrame4_Produtos, text="Adicione uma imagem", command=lambda : self.inserir_imagem() ,bg='#2E3133', font=("", 10, "bold"), fg='white',cursor='hand2', activebackground='#FD9C3A', bd=5, width=20)
        self.nova_imagem.place(x=79, y=240)

        ### Try que serve para abrir o programa sem imagem, e quando tiver imagem vai aparecer acho(?)
        try:
            ImagemProduto = ImageTk.PhotoImage(Image.open('../img/semImagem.png').resize((250, 135)))
            self.logo = Label(self.bodyFrame4_Produtos, image=ImagemProduto, bg='#2E3133',bd=1)
            self.logo.image = ImagemProduto
            self.logo.place(x=15, y=275)
        except FileNotFoundError:
            hora_erro = datetime.now().strftime("%H:%M:%S")
            print(f"Erro: Imagem não Encontrada - {hora_erro}")


        ##############################################################################################################################################
        ##################################################### FRAME Clientes #########################################################################
        ##############################################################################################################################################

        # Frame Clientes
        self.frameClientes = Frame(self.window, bg="#17191F")
        self.frameClientes.place(x=300, y=0, width=1366, height=768)

        ## Label Clientes
        self.heading_Clientes = Label(self.frameClientes, text="Clientes", font=("", 13, "bold"), fg='white',bg='#17191F')
        self.heading_Clientes.place(x=25, y=50)

        self.line_Clientes = Label(self.frameClientes, text="____________", font=("", 10, "bold"), fg='#FD9C3A',bg='#17191F')
        self.line_Clientes.place(x=25, y=25)

        #Icon de aviso para dizer oq a pagina faz (Pagina Clientes)
        self.prety_icon_aviso_Clientes = Label(self.frameClientes, image=AvisoImagem, bg='#17191F')
        self.prety_icon_aviso_Clientes.image = AvisoImagem
        self.prety_icon_aviso_Clientes.place(x=100, y=45)
        self.prety_icon_aviso_Clientes.bind("<Button-1>",lambda event: messagebox.showinfo("Aviso!", "Esta é a pagina dos Clientes, aqui podes ver os dados dos clientes e uma lista de quais são os melhores compradores."))


        ## Frame 1 da listagem dos Clientes
        self.bodyFrame1_Clientes = Frame(self.frameClientes, bg="#2E3133")
        self.bodyFrame1_Clientes.place(x=28, y=90, width=660, height=650)

        ### Treeview que mostra todos os Produtos na base de dados
        self.clientes_lista =tkinter.ttk.Treeview(self.bodyFrame1_Clientes, columns=("col1", "col2", "col3","col4","col5","col6"))
        self.clientes_lista.heading("#0", text="")
        self.clientes_lista.heading("#1", text="Cliente", anchor='w')
        self.clientes_lista.heading("#2", text="Username", anchor='w')
        self.clientes_lista.heading("#3", text="Nome", anchor='w')
        self.clientes_lista.heading("#4", text="Contacto", anchor='w')
        self.clientes_lista.heading("#5", text="Email", anchor='w')
        self.clientes_lista.heading("#6", text="Password", anchor='w')
        self.clientes_lista.column("#0", width=5, stretch=NO)
        self.clientes_lista.column("#1", width=50, stretch=NO)
        self.clientes_lista.column("#2", width=100, stretch=NO)
        self.clientes_lista.column("#3", width=100, stretch=NO)
        self.clientes_lista.column("#4", width=100, stretch=NO)
        self.clientes_lista.column("#5", width=175, stretch=NO)
        self.clientes_lista.column("#6", width=127, stretch=NO)
        self.clientes_lista.place(relx=-0.009, rely=0, relwidth=1.001, relheight=1.05)

        ### Sroll Bar
        self.sroll = Scrollbar(self.bodyFrame1_Clientes, orient="vertical")
        self.sroll.configure(command=self.clientes_lista.yview)
        self.sroll.place(relx=0.94, rely=0.04, relwidth=0.051, relheight=0.96)
        self.clientes_lista.configure(yscroll=self.sroll.set)
        self.lista_clientes()

        ## Frame 2 (Quantidade de Clientes)
        self.bodyFrame3_Clientes = Frame(self.frameClientes, bg="#2E3133")
        self.bodyFrame3_Clientes.place(x=730, y=90, width=310, height=150)

        ### Label a dizer Clientes
        self.labelFrame3_Clientes = Label(self.bodyFrame3_Clientes, bg="#2E3133", text="Total de Clientes:",font=("", 15, "bold"), fg='white')
        self.labelFrame3_Clientes.place(x=60, y=25)

        ### Linha
        self.lineFrame3_Produtos = Label(self.bodyFrame3_Clientes, text="________________________",font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.lineFrame3_Produtos.place(x=60, y=0)

        ###Numero de Clientes
        self.N_produtosFrame3_Clientes = Label(self.bodyFrame3_Clientes, bg="#2E3133", text=int(self.contar_clientes()),font=("", 50, "bold"), fg='white')
        self.N_produtosFrame3_Clientes.place(x=115, y=65)

        ###Imagem de uma pessoa (para estetica)
        self.prety_icon_clientes_Clientes = Label(self.bodyFrame3_Clientes, image=ClientesImage, bg='#2E3133')
        self.prety_icon_clientes_Clientes.image = ClientesImage
        self.prety_icon_clientes_Clientes.place(x=25, y=21)

        ## Frame 3 (Melhores Compradores)
        self.bodyFrame4_Clientes = Frame(self.frameClientes, bg="#2E3133")
        self.bodyFrame4_Clientes.place(x=730, y=285, width=310, height=455)

        ### Label a dizer Melhores Compradores
        self.LabelMelhoresCompradores = Label(self.bodyFrame4_Clientes, bg="#2E3133", text="Melhores Compradores", font=("", 15, "bold"),fg='white')
        self.LabelMelhoresCompradores.place(x=25, y=25)

        ### Linha
        self.lineFrame4_Clientes = Label(self.bodyFrame4_Clientes, text="______________________________",font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.lineFrame4_Clientes.place(x=25, y=0)

        ### Icon de aviso para dizer oq a pagina faz (Pagina Melhores compradores)
        self.prety_icon_aviso_bestClientes = Label(self.bodyFrame4_Clientes, image=AvisoImagem, bg='#2E3133')
        self.prety_icon_aviso_bestClientes.image = AvisoImagem
        self.prety_icon_aviso_bestClientes.place(x=255, y=25)
        self.prety_icon_aviso_bestClientes.bind("<Button-1>", lambda event: messagebox.showinfo("Aviso!","Esta é a lista dos melhores, podes ver o nome de cada clientes e a quantidade de encomendas que cada um fez."))

        ### Treeview que mostra os clientes com mais encomendas
        self.clientes_encomendas_lista =tkinter.ttk.Treeview(self.bodyFrame4_Clientes, columns=("col1", "col2", "col3","col4"))
        self.clientes_encomendas_lista.heading("#0", text="")
        self.clientes_encomendas_lista.heading("#1", text="Cliente", anchor='w')
        self.clientes_encomendas_lista.heading("#2", text="Nome", anchor='w')
        self.clientes_encomendas_lista.heading("#3", text="Encomendas", anchor='w')
        self.clientes_encomendas_lista.column("#0", width=3, stretch=NO)
        self.clientes_encomendas_lista.column("#1", width=50, stretch=NO)
        self.clientes_encomendas_lista.column("#2", width=150, stretch=NO)
        self.clientes_encomendas_lista.column("#3", width=103, stretch=NO)
        self.clientes_encomendas_lista.column("#4", width=1, stretch=NO)
        self.clientes_encomendas_lista.place(relx=-0.009, rely=0.15, relwidth=1.001, relheight=0.87)

        ### Sroll Bar
        self.sroll = Scrollbar(self.bodyFrame4_Clientes, orient="vertical")
        self.sroll.configure(command=self.clientes_encomendas_lista.yview)
        self.sroll.place(relx=0.94, rely=0.209, relwidth=0.051, relheight=0.79)
        self.clientes_encomendas_lista.configure(yscroll=self.sroll.set)
        self.lista_clientes_encomendas()

        ##############################################################################################################################################
        ##################################################### Frame Encomendas #######################################################################
        ##############################################################################################################################################

        # Frame Encomendas
        self.frameEncomendas = Frame(self.window, bg="#17191F")
        self.frameEncomendas.place(x=300, y=0, width=1366, height=768)

        ## Label Encomendas
        self.heading_Encomendas = Label(self.frameEncomendas, text="Encomendas", font=("", 13, "bold"), fg='white',bg='#17191F')
        self.heading_Encomendas.place(x=25, y=50)

        ## Linha
        self.line_Clientes = Label(self.frameEncomendas, text="____________", font=("", 10, "bold"), fg='#FD9C3A',bg='#17191F')
        self.line_Clientes.place(x=25, y=25)

        #Aviso de Como funciona a encomenda
        self.prety_icon_aviso_Encomendas = Label(self.frameEncomendas, image=AvisoImagem, bg='#17191F')
        self.prety_icon_aviso_Encomendas.image = AvisoImagem
        self.prety_icon_aviso_Encomendas.place(x=130, y=45)
        self.prety_icon_aviso_Encomendas.bind("<Button-1>",lambda event: messagebox.showinfo("Aviso!", "Esta é a pagina das Encomendas, aqui podes ver e fazer as encomendas todas."))

        ## Frame (Tudo)
        self.frameListaClientes = Frame(self.frameEncomendas, bg="#2E3133")
        self.frameListaClientes.place(x=28, y=90, width=1012, height=650)

        ### Label a dizer Metedo de Pagamento
        self.LabelFazer_Encomenda = Label(self.frameListaClientes, bg="#2E3133", text="Metedo de Pagamento", font=("", 12, "bold"),fg='white')
        self.LabelFazer_Encomenda.place(x=420, y=25)

        ### Combobox para o método de pagamento 0=Em mãos, 1=Mbway
        self.Combo_pagamento = ttk.Combobox(self.frameListaClientes, state="readonly", background="#2E3133",foreground='#FD9C3A', font=("", 10, "bold"), width=26,values=['Pagamento em Mãos', 'MBway'])
        self.Combo_pagamento.place(x=420, y=50)


        ### Label a dizer Método de Entrega
        self.LabelFazer_Encomenda = Label(self.frameListaClientes, bg="#2E3133", text="Método de Entrega",font=("", 12, "bold"), fg='white')
        self.LabelFazer_Encomenda.place(x=640, y=25)

        ### Combobox para o método de levantamento 0=Pickup , 1=Entrega ao domicilio
        self.Combo_levar = ttk.Combobox(self.frameListaClientes, state="readonly", background="#2E3133",foreground='#FD9C3A', font=("", 10, "bold"), width=26,values=['Pickup', 'Entrega ao Domicílio'])
        self.Combo_levar.place(x=640, y=50)

        ### Label a dizer fazer encomendas
        self.LabelFazer_Encomenda = Label(self.frameListaClientes, bg="#2E3133", text="Fazer Encomenda", font=("", 15, "bold"),fg='white')
        self.LabelFazer_Encomenda.place(x=25, y=25)

        ### Linha
        self.line_FazerEncomenda = Label(self.frameListaClientes, text="______________________________",font=("", 10, "bold"), fg='#FD9C3A', bg='#2E3133')
        self.line_FazerEncomenda.place(x=25, y=0)

        #Aviso de Como funciona a encomenda
        self.prety_icon_aviso_fazer_Encomendas = Label(self.frameListaClientes, image=AvisoImagem, bg='#2E3133')
        self.prety_icon_aviso_fazer_Encomendas.image = AvisoImagem
        self.prety_icon_aviso_fazer_Encomendas.place(x=220, y=25)
        self.prety_icon_aviso_fazer_Encomendas.bind("<Button-1>",lambda event: messagebox.showinfo("Aviso!", "Como fazer uma encomenda:\n\n1)Selecione um cliente;\n2)Depois para adicionar um produto só tem de mudar a quantidade dele para algo maior que 0;\n3)Se quiser esse produto frito ou congelado é so marcar a caixa ao lado desse mesmo produto (só rissois seram fritos e congelados);\n4)Depois é confirmar a encomenda e ela vai aparecer na janela ao lado."))

        ### Treeview que mostra os clientes para selecionar para a compra
        self.clientes_lista_encomendas =tkinter.ttk.Treeview(self.frameListaClientes, columns=("col1"))
        self.clientes_lista_encomendas.heading("#0", text="")
        self.clientes_lista_encomendas.heading("#1", text="Cliente", anchor='w')
        self.clientes_lista_encomendas.column("#0", width=3, stretch=NO)
        self.clientes_lista_encomendas.column("#1", width=125, stretch=NO)
        self.clientes_lista_encomendas.place(relx=0.04, rely=0.15, relwidth=0.13, relheight=0.81)

        ### Sroll Bar
        self.sroll = Scrollbar(self.clientes_lista_encomendas, orient="vertical")
        self.sroll.configure(command=self.clientes_lista_encomendas.yview)
        self.sroll.place(relx=0.8, rely=0.05, relwidth=0.2, relheight=0.95)
        self.clientes_lista_encomendas.configure(yscroll=self.sroll.set)
        self.lista_nome_clientes()

        ### Label a dizer Selecionar Cliente
        self.heading_select_clientes = Label(self.frameListaClientes, text="Selecionar Cliente", font=("", 13, "bold"), fg='white',bg='#2E3133')
        self.heading_select_clientes.place(x=35, y=75)

        ### Frame para tratar dos produtos
        self.frameProdutos_Encomendas = Frame(self.frameListaClientes, bg="#2E3133")
        self.frameProdutos_Encomendas.place(x=198, y=100, width=200, height=523)

        ### Label a dizer Selecionar Produtos
        self.heading_select_prod = Label(self.frameListaClientes, text="Selecionar Produtos", font=("", 13, "bold"), fg='white',bg='#2E3133')
        self.heading_select_prod.place(x=198, y=75)

        ### Criação de Frames e Canvas para dar scroll
        self.canvas_produtos = Canvas(self.frameProdutos_Encomendas, bg="#2E3133")
        self.canvas_produtos.place(relx=0, rely=0, relwidth=1, relheight=1)
        self.frame_checkbuttons = Frame(self.canvas_produtos, bg='#2E3133')
        self.frame_checkbuttons.place(relx=0.1, rely=0.2, relwidth=0.95, relheight=0.50)
        self.canvas_produtos.create_window((0, 0), window=self.frame_checkbuttons, anchor="nw")

        ### Scroll bar
        self.canvas_produtos.config(scrollregion=self.canvas_produtos.bbox("all"))
        self.scroll_canvas = Scrollbar(self.frameProdutos_Encomendas, orient="vertical",command=self.canvas_produtos.yview)
        self.scroll_canvas.place(relx=0.89, rely=0, relwidth=0.1, relheight=1)
        self.canvas_produtos.configure(yscrollcommand=self.scroll_canvas.set)
        self.produtos_encomendas()

        ### Imagem de certo para confirmar
        self.aceitar_encomenda = Label(self.frameListaClientes, image=CertoImage, bg='#2E3133')
        self.aceitar_encomenda.image = CertoImage
        self.aceitar_encomenda.place(x=260, y=25)
        self.aceitar_encomenda.bind("<Button-1>", lambda event: self.fazer_encomendas())

        ### Frame para tratar das encomendas
        self.ver_encomendas = Frame(self.frameListaClientes, bg="#2E3133")
        self.ver_encomendas.place(x=420, y=100, width=550, height=523)

        ### Label a dizer Encomendas
        self.heading_select_encomendas = Label(self.frameListaClientes, text="Encomendas", font=("", 13, "bold"),fg='white', bg='#2E3133')
        self.heading_select_encomendas.place(x=420, y=75)

        ### Criação de Frames e Canvas para dar scroll
        self.canvas_encomendas = Canvas(self.ver_encomendas, bg="#2E3133")
        self.canvas_encomendas.place(relx=0, rely=0, relwidth=1, relheight=1)
        self.frame_encomendas = Frame(self.canvas_encomendas, bg='#2E3133')
        self.frame_encomendas.place(relx=0.1, rely=0.2, relwidth=0.95, relheight=0.50)

        ### Scroll bar
        self.canvas_encomendas.create_window((0, 0), window=self.frame_encomendas, anchor="nw")
        self.canvas_encomendas.config(scrollregion=self.canvas_encomendas.bbox("all"))
        self.scroll_canvas_encomendas = Scrollbar(self.ver_encomendas, orient="vertical",command=self.canvas_encomendas.yview)
        self.scroll_canvas_encomendas.place(relx=0.95, rely=0, relwidth=0.05, relheight=1)
        self.canvas_encomendas.configure(yscrollcommand=self.scroll_canvas_encomendas.set)
        self.mostrar_encomendas()


        ##############################################################################################################################################
        ##################################################### Frame Lucro ############################################################################
        ##############################################################################################################################################

        #Frame do Lucro
        self.frameLucro = Frame(self.window, bg="#17191F")
        self.frameLucro.place(x=300, y=0, width=1366, height=768)

        ## Label Encomendas
        self.heading_Lucro = Label(self.frameLucro, text="Lucro e Estatísticas", font=("", 13, "bold"), fg='white',bg='#17191F')
        self.heading_Lucro.place(x=25, y=50)

        ## Linha
        self.line_Clientes = Label(self.frameLucro, text="____________", font=("", 10, "bold"), fg='#FD9C3A',bg='#17191F')
        self.line_Clientes.place(x=25, y=25)

        ## Aviso de Como funciona a encomenda
        self.prety_icon_aviso_Lucro = Label(self.frameLucro, image=AvisoImagem, bg='#17191F')
        self.prety_icon_aviso_Lucro.image = AvisoImagem
        self.prety_icon_aviso_Lucro.place(x=200, y=45)
        self.prety_icon_aviso_Lucro.bind("<Button-1>", lambda event: messagebox.showinfo("Aviso!","Esta é a pagina do Lucro, aqui podes ver o teu lucro e as estatisticas das tuas vendas."))

        ## Frame que vai ter tudo (Lucro e Stats)
        self.frameLucro_stats = Frame(self.frameLucro, bg="#2E3133")
        self.frameLucro_stats.place(x=28, y=90, width=1012, height=650)

        ###Treeview par mostrar os meses que lucram mais
        self.lista_lucro =tkinter.ttk.Treeview(self.frameLucro_stats, columns=("col1", "col2"))
        self.lista_lucro.heading("#0", text="")
        self.lista_lucro.heading("#1", text="Mês", anchor='w')
        self.lista_lucro.heading("#2", text="Total", anchor='w')
        self.lista_lucro.column("#0", width=3, stretch=NO)
        self.lista_lucro.column("#1", width=75, stretch=NO)
        self.lista_lucro.column("#2", width=122, stretch=NO)
        self.lista_lucro.place(relx=0.04, rely=0.15, relwidth=0.20, relheight=0.81)
        self.lista_lucro.bind("<<TreeviewSelect>>", lambda event: self.Lucro_mes())

        ### Sroll Bar
        self.sroll = Scrollbar(self.lista_lucro, orient="vertical")
        self.sroll.configure(command=self.lista_lucro.yview)
        self.sroll.place(relx=0.87, rely=0.05, relwidth=0.13, relheight=0.95)
        self.lista_lucro.configure(yscroll=self.sroll.set)
        self.Lucro_ano()

        ### Combo box com o ano
        self.Combo_ano = ttk.Combobox(self.frameLucro_stats, state="readonly", background="#2E3133",foreground='#FD9C3A', font=("", 10, "bold"),width=26)
        self.Combo_ano.place(x=41, y=70)
        self.AnosServico()
        self.Combo_ano.bind("<<ComboboxSelected>>", lambda event: self.Lucro_ano())

        ##############################################################################################################################################
        ##################################################### Frame Mensagens ########################################################################
        ##############################################################################################################################################

        #Frame das Mensagens
        self.frameMensagens = Frame(self.window, bg="#17191F")
        self.frameMensagens.place(x=300, y=0, width=1366, height=768)

        ## Frame que vai ter as Mensagens todas
        self.frameAll_Mensagens = Frame(self.frameMensagens, bg="#2E3133")
        self.frameAll_Mensagens.place(x=28, y=90, width=1012, height=650)

        ## Label Mensagens
        self.heading_Mensagens = Label(self.frameMensagens, text="Mensagens", font=("", 13, "bold"), fg='white',bg='#17191F')
        self.heading_Mensagens.place(x=25, y=50)

        ## Linha
        self.line_Mensagens = Label(self.frameMensagens, text="____________", font=("", 10, "bold"), fg='#FD9C3A',bg='#17191F')
        self.line_Mensagens.place(x=25, y=25)

        ## Aviso de Como funciona a encomenda
        self.prety_icon_aviso_Mensagens = Label(self.frameMensagens, image=AvisoImagem, bg='#17191F')
        self.prety_icon_aviso_Mensagens.image = AvisoImagem
        self.prety_icon_aviso_Mensagens.place(x=125, y=45)
        self.prety_icon_aviso_Mensagens.bind("<Button-1>", lambda event: messagebox.showinfo("Aviso!","Esta é a pagina das Mensagens, aqui podes ver as mensagens que os teus clientes deixaram no site."))

        ## Frame que vai ter as mensagen
        self.ver_mensagens = Frame(self.frameAll_Mensagens, bg="#2E3133")
        self.ver_mensagens.place(x=50, y=50, width=900, height=623)

        ### Criação de Frames e Canvas para dar scroll
        self.canvas_mensagens = Canvas(self.ver_mensagens, bg="#2E3133")
        self.canvas_mensagens.place(relx=0, rely=0, relwidth=1, relheight=1)
        self.frame_mensagens = Frame(self.canvas_mensagens, bg='#2E3133')
        self.frame_mensagens.place(relx=0.1, rely=0.2, relwidth=0.95, relheight=0.50)

        ### Scroll bar
        self.canvas_mensagens.create_window((0, 0), window=self.frame_mensagens, anchor="nw")
        self.canvas_mensagens.config(scrollregion=self.canvas_mensagens.bbox("all"))
        self.scroll_canvas_mensagens = Scrollbar(self.ver_mensagens, orient="vertical",command=self.canvas_mensagens.yview)
        self.scroll_canvas_mensagens.place(relx=0.95, rely=0, relwidth=0.05, relheight=1)
        self.scroll_canvas_mensagens.config(command=self.canvas_mensagens.yview)
        self.mostrar_mensagens()

        #Fazer numero de mensagens e quando mostrar as mensagens mostrar a foto de perfil
        #Tratar da imagem

        #Puxar a janela do inicio para cima quando o programa abrir
        self.frameInicio.lift()
def win():
    window = Tk()
    Dashboard(window)
    window.mainloop()

if __name__ == "__main__":
    win()
