#!/bin/bash

# Diretório / do site
SERVERPATH=$1

# Nome do diretório onde irão os arquivos do novo site
NOVO_DIR=$2

# Cria a estrutura default
echo "Movendo: ""$SERVERPATH""$NOVO_DIR para /tmp/"
/bin/mv "$SERVERPATH""$NOVO_DIR" "/tmp/"
