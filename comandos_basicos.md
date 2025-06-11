
# 🐳 Comandos básicos Docker Compose (CORPU)

Este proyecto tiene **dos entornos separados**:

- PHP 5.3 → `docker-compose.yml`
- PHP 8.1 → `docker-compose-php8.1.yml`

---

## 🚀 Crear y levantar contenedor

```bash
docker compose -f docker-compose-php8.1.yml build --no-cache

docker compose -f docker-compose-php8.1.yml up -d
```

### PHP 8.1

```bash
docker-compose -f docker-compose-php8.1.yml up -d --build
```

---

## ⏹ Parar contenedor


### PHP 8.1

```bash
docker-compose -f docker-compose-php8.1.yml down
```

---

## 🔁 Reiniciar contenedor

### PHP 5.3

```bash
docker-compose -f docker-compose.yml restart
```

### PHP 8.1

```bash
docker-compose -f docker-compose-php8.1.yml restart
```

---

## 🧼 Borrar TODO lo relacionado con Docker (⚠ cuidado)

```bash
docker-compose -f docker-compose.yml down -v --rmi all
docker-compose -f docker-compose-php8.1.yml down -v --rmi all
```

Y si quieres aún más limpieza:

```bash
docker system prune -a --volumes
```

---

## 🐚 Entrar al contenedor

### PHP 5.3

```bash
docker exec -it corpu-5.3 bash
```

### PHP 8.1

```bash
docker exec -it corpu-8.1 bash
```

---

## 📂 Ver logs

### PHP 5.3

```bash
docker logs -f corpu-5.3
```

### PHP 8.1

```bash
docker logs -f corpu-8.1
```

---

## ✅ Comprobaciones rápidas

Ver contenedores activos:

```bash
docker ps
```

Ver imágenes:

```bash
docker images
```

---

## 🔗 Variables de entorno

No olvides configurar `.env` o exportar variables como:

```bash
export BRANCH_PATH_8_1=D:/Proxectos/CORPU/svn/branches/corpu-8.1/corpu-web/src/main/php
```
