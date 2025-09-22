# Etapa 1: build
FROM node:22 AS builder

WORKDIR /usr/src/app

# Copia apenas package.json e package-lock.json para instalar dependências primeiro
COPY package*.json ./

# Instala dependências
RUN npm install

# Copia o resto do código
COPY . .

# Faz o build da aplicação
RUN npm run build

# Etapa 2: runtime (imagem final menor e otimizada)
FROM node:22-slim

WORKDIR /usr/src/app

# Copia apenas arquivos necessários para rodar
COPY --from=builder /usr/src/app/.output ./.output
COPY --from=builder /usr/src/app/node_modules ./node_modules
COPY --from=builder /usr/src/app/package*.json ./

EXPOSE 3000

# Usa o servidor do Nuxt
CMD ["node", ".output/server/index.mjs"]