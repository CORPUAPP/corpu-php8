# CORPU - Entorno Docker PHP 8.1

Este proyecto monta un entorno de desarrollo **Dockerizado** para **CakePHP 2.x migrado a PHP 8.1**, con soporte para **Oracle** y configuración personalizada.

---

## 🧱 Estructura del proyecto

```bash
/docker-php81/
├── docker-compose.yml
├── Dockerfile.8.1
├── docker-php/
│   ├── docker-hosts/hosts
│   ├── reports/
│   ├── ...
├── corpu-web/           # Código fuente del proyecto CakePHP
├── corpu-config/        # Configuración externa del sistema
├── logs/
└── ...
```

---

## 🚀 Primeros pasos

### 1. Clonar el proyecto

```bash
git clone git@github.com:CORPUAPP/docker-php81.git
cd docker-php81
```

> Si no tienes permisos, asegúrate de tener configurado el SSH correctamente con `ssh -T git@github.com`.

---

### 2. Variables de entorno necesarias

Crea un `.env` o exporta estas variables directamente:

```bash
export BRANCH_PATH_8_1=D:/Proxectos/CORPU/svn/branches/corpu-8.1/corpu-web/src/main/php
export CONFIGURED_PATH=D:/Proxectos/CORPU/config
export APPS_CONFIG_PATH_8_1=D:/Proxectos/CORPU/svn/branches/corpu-8.1/corpu-config
export CONFIGURED_LOG_APACHE=D:/Proxectos/CORPU/logs/apache
export CORPU_FILES=D:/Proxectos/CORPU/files
```

---

### 3. Levantar el contenedor

```bash
docker compose -f docker-compose.yml up --build -d
```

Verifica que está corriendo con:

```bash
docker ps
```

Accede en el navegador a:  
👉 `http://localhost:8081`

---

## 🔧 Comandos útiles

### Ver logs del contenedor

```bash
docker logs -f corpu-8.1
```

### Acceder al contenedor

```bash
docker exec -it corpu-8.1 bash
```

### Reiniciar contenedor

```bash
docker compose restart
```

---

## 🐘 Apache y PHP

- El `DocumentRoot` apunta a `/var/www/html`, que se monta desde `${BRANCH_PATH_8_1}`
- Asegúrate de tener un `index.php` en esa carpeta o Apache dará error 403
- Apache usa configuración por defecto, si quieres cambiarla deberías montar tu propia conf

---

## 🐍 Oracle y AppUtil

- Se añadió la clase `Oracle` que extiende `DboSource` para que funcione con PHP 8.1
- También se incorporó `AppUtil` manualmente en `/lib/` para mantener trazas y utilidades

---

## 🛑 Problemas conocidos

### 1. SVN error al hacer commit (`MKCOL not allowed`)
Solución:
```bash
svn add corpu-config
svn commit -m "Añadiendo carpeta de configuración"
```

### 2. Error `403 Forbidden`
Revisa:
- Que `index.php` esté en la raíz del proyecto montado
- Que la carpeta no tenga permisos de solo lectura para Apache

---

## 🧼 Ignorar carpetas innecesarias

Para ignorar `target/` en SVN:
```bash
svn propset svn:ignore target .
svn commit -m "Ignorar carpeta target"
```

---

## 📌 Tips

- Si Visual Studio Code no te detecta PHP 8.1: configura manualmente `"php.validate.executablePath"` apuntando a tu ejecutable dentro del contenedor o un PHP 8.1 instalado en local.
- Verifica que `php.ini` y `composer` estén correctamente montados si los necesitas.

---

## 🤘 Mantenido por

**maregon** - 