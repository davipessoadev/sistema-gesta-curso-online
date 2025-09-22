# ---------- STAGE 1: BUILD ----------
FROM node:22 AS build

WORKDIR /usr/src/app

# Copia apenas os manifests primeiro (melhora cache do Docker)
COPY package*.json ./

# Instala dependências (sem cache, garantindo bindings corretos)
RUN npm install

# Copia o restante do código
COPY . .

# Gera o build do Nuxt
RUN npm run build


# ---------- STAGE 2: PRODUCTION ----------
FROM node:22-slim AS production

WORKDIR /usr/src/app

# Atualiza pacotes do sistema para corrigir vulnerabilidades
RUN apt-get update && apt-get upgrade -y && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia apenas os arquivos de build gerados
COPY --from=build /usr/src/app/.output ./

EXPOSE 3000

CMD ["node", "server/index.mjs"]
