#!/bin/bash

# Diretório / do site
SERVERPATH=$1

# Nome do diretório onde irão os arquivos do novo site
NOME_VELHO=$2

# Nome do diretório onde irão os arquivos do novo site
NOME_NOVO=$3

# Cria a estrutura default
echo "Movendo: ""$SERVERPATH""$NOME_VELHO"" para ""$SERVERPATH""$NOME_NOVO"
echo "$SERVERPATH""$NOME_VELHO"" ""$SERVERPATH""$NOME_NOVO"
/bin/mv "$SERVERPATH""$NOME_VELHO" "$SERVERPATH""$NOME_NOVO"
