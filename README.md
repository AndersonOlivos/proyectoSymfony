🎾 PadelRank. - Plataforma de Valoración de Jugadores
=====================================================

**PadelRank** es una aplicación web interactiva diseñada para la comunidad de pádel. Su objetivo principal es generar rankings dinámicos basados exclusivamente en la opinión y el *feedback* cualitativo de los usuarios, permitiendo valorar el rendimiento de jugadores tanto profesionales como amateurs.

📖 Sobre el Proyecto
--------------------

A diferencia de los rankings oficiales basados en puntos de torneo, **PadelRank** ofrece una visión social. El sistema centraliza las opiniones de los aficionados para destacar no solo a los ganadores, sino a los jugadores mejor valorados por su técnica, carisma o evolución.

La plataforma cuenta con una arquitectura robusta que garantiza que cada usuario pueda expresar su opinión de forma única y segura, evitando duplicidades en las votaciones.

✨ Funcionalidades Destacadas
----------------------------

### 👤 Experiencia del Usuario

-   **Sistema de Reviews:** Los usuarios registrados pueden valorar a los jugadores con puntuaciones de 1 a 5 estrellas y añadir reseñas textuales.

-   **Restricción de Voto Único:** El sistema garantiza que un usuario solo pueda valorar una vez a un jugador específico, asegurando la integridad de los rankings.

-   **Exploración y Filtrado:** Buscador de jugadores con filtrado por categorías (Premier Padel, 1ª División, 2ª División, etc.).

-   **Interfaz Moderna:** Diseño visualmente atractivo utilizando gradientes personalizados de Tailwind CSS, con un estilo elegante y profesional.

### 📊 Panel de Estadísticas y Administración

El proyecto incluye un completo Dashboard de análisis para supervisar la actividad:

-   **Métricas Clave (KPIs):** Visualización del total de jugadores registrados, volumen de valoraciones y detección automática del **MVP** (Jugador Mejor Valorado).

-   **Análisis de Categorías:** Desglose de satisfacción y nivel de actividad por categorías mediante indicadores visuales.

-   **Top Performance:** Tablas dinámicas que muestran a los jugadores con mejor nota media calculada en tiempo real.

-   **Gestión de Contenido:** Herramientas para la moderación de reseñas y administración de perfiles.

🛠️ Tecnologías Utilizadas
--------------------------

-   **Framework:** Symfony 8 (PHP 8.2+).

-   **Estilos:** Tailwind CSS (Diseño responsive y componentes personalizados).

-   **Base de Datos:** MySQL con Doctrine ORM (Consultas optimizadas para el cálculo de medias y rankings).

-   **Motor de Plantillas:** Twig.

-   **Seguridad:** Symfony Security con control de acceso por roles (`ROLE_USER` y `ROLE_ADMIN`).
