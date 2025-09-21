FROM node:22 AS build

WORKDIR /usr/src/app

COPY package*.json ./

COPY . .

RUN npm run build

FROM node:22 AS production

WORKDIR /usr/src/app

COPY --from=build /usr/src/app/.output ./

EXPOSE 3000

CMD ["node", "server/index.mjs"]