# Dockerfile para el frontend
# Usamos Nginx para servir archivos estáticos
FROM nginx:alpine

# Carpeta de trabajo dentro del contenedor
WORKDIR /usr/share/nginx/html

# Copiamos todo el contenido del frontend al contenedor
COPY . .

# Exponemos el puerto 80
EXPOSE 80

# Comando por defecto de Nginx
CMD ["nginx", "-g", "daemon off;"]
