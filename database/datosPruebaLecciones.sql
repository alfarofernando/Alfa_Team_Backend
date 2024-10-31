-- Insertar datos de prueba en la tabla lecciones para cada curso

-- Lecciones para "Python para Principiantes"
INSERT INTO lecciones (curso_id, title, type, content)
VALUES
(1, 'Introducción a Python', 'text', 'Contenido de introducción a Python...'),
(1, 'Instalación de Python', 'video', 'Video explicativo de instalación de Python...'),
(1, 'Sintaxis Básica', 'text', 'Contenido sobre la sintaxis básica de Python...'),
(1, 'Variables y Tipos de Datos', 'video', 'Video sobre variables y tipos de datos en Python...'),
(1, 'Operadores y Expresiones', 'text', 'Explicación sobre operadores y expresiones en Python...'),
(1, 'Estructuras Condicionales', 'video', 'Video sobre if, else, y elif en Python...'),
(1, 'Bucles en Python', 'text', 'Contenido sobre el uso de bucles for y while...'),
(1, 'Funciones en Python', 'video', 'Video explicando cómo crear funciones en Python...'),
(1, 'Manejo de Errores', 'text', 'Explicación de manejo de errores y excepciones...'),
(1, 'Proyecto Final: Calculadora Básica', 'video', 'Video del proyecto final: Calculadora en Python...');

-- Lecciones para "Desarrollo Web Full Stack"
INSERT INTO lecciones (curso_id, title, type, content)
VALUES
(2, 'Introducción al Desarrollo Web', 'text', 'Conceptos básicos sobre desarrollo web...'),
(2, 'HTML: Fundamentos', 'video', 'Video explicativo de HTML básico...'),
(2, 'CSS: Estilizando Páginas Web', 'text', 'Contenido sobre CSS básico...'),
(2, 'JavaScript: Fundamentos', 'video', 'Video introductorio a JavaScript...'),
(2, 'Maquetación con Flexbox y Grid', 'text', 'Lección sobre maquetación con CSS...'),
(2, 'Introducción a React', 'video', 'Video explicativo sobre React...'),
(2, 'Creación de APIs con Node.js', 'text', 'Lección sobre APIs en Node.js...'),
(2, 'Base de Datos con MongoDB', 'video', 'Introducción a MongoDB para bases de datos...'),
(2, 'Autenticación y Seguridad', 'text', 'Lección sobre seguridad en desarrollo web...'),
(2, 'Proyecto Final: Blog Completo', 'video', 'Video del proyecto final: Blog con React y Node.js...');

-- Lecciones para "Diseño Gráfico con Adobe Illustrator"
INSERT INTO lecciones (curso_id, title, type, content)
VALUES
(3, 'Introducción a Illustrator', 'video', 'Contenido sobre el entorno de trabajo de Illustrator...'),
(3, 'Herramientas de Selección', 'text', 'Uso de herramientas de selección en Illustrator...'),
(3, 'Capas y Grupos', 'video', 'Lección sobre la gestión de capas y grupos...'),
(3, 'Herramientas de Dibujo', 'text', 'Explicación sobre las herramientas de dibujo...'),
(3, 'Colores y Degradados', 'video', 'Lección sobre aplicar colores y degradados...'),
(3, 'Efectos y Estilos', 'text', 'Explicación sobre el uso de efectos...'),
(3, 'Texto y Tipografía', 'video', 'Lección sobre el uso de texto y tipografía...'),
(3, 'Exportar Archivos', 'text', 'Cómo exportar archivos en Illustrator...'),
(3, 'Creación de Logotipos', 'video', 'Lección sobre diseño de logotipos...'),
(3, 'Proyecto Final: Tarjeta de Visita', 'text', 'Creación de una tarjeta de visita en Illustrator...');

-- Lecciones para "Introducción a la Inteligencia Artificial"
INSERT INTO lecciones (curso_id, title, type, content)
VALUES
(4, 'Historia de la Inteligencia Artificial', 'text', 'Historia y evolución de la IA...'),
(4, 'Conceptos Básicos de IA', 'video', 'Lección sobre conceptos básicos de IA...'),
(4, 'Machine Learning vs. IA', 'text', 'Diferencias entre Machine Learning e IA...'),
(4, 'Algoritmos de Clasificación', 'video', 'Explicación de algoritmos de clasificación...'),
(4, 'Redes Neuronales', 'text', 'Introducción a redes neuronales...'),
(4, 'Regresión Lineal y Logística', 'video', 'Lección sobre regresión en IA...'),
(4, 'Clusterización de Datos', 'text', 'Explicación de clusterización de datos...'),
(4, 'Introducción a NLP', 'video', 'Conceptos básicos de procesamiento de lenguaje natural...'),
(4, 'Ética en IA', 'text', 'Aspectos éticos en inteligencia artificial...'),
(4, 'Proyecto Final: Predicción de Datos', 'video', 'Video del proyecto final de predicción...');

-- Lecciones para "Desarrollo de Aplicaciones Android"
INSERT INTO lecciones (curso_id, title, type, content)
VALUES
(5, 'Introducción a Android', 'text', 'Conceptos básicos de desarrollo Android...'),
(5, 'Instalación de Android Studio', 'video', 'Instalación y configuración de Android Studio...'),
(5, 'Interfaz de Usuario', 'text', 'Diseño de la interfaz de usuario...'),
(5, 'Layouts en Android', 'video', 'Gestión de layouts en Android...'),
(5, 'Actividades y Fragmentos', 'text', 'Uso de actividades y fragmentos...'),
(5, 'Navegación en Aplicaciones', 'video', 'Lección sobre navegación...'),
(5, 'Conexión a Bases de Datos', 'text', 'Uso de SQLite en Android...'),
(5, 'Autenticación de Usuario', 'video', 'Implementación de autenticación...'),
(5, 'Notificaciones Push', 'text', 'Configuración de notificaciones push...'),
(5, 'Proyecto Final: To-Do App', 'video', 'Proyecto final de lista de tareas en Android...');

-- Lecciones para "SQL para Análisis de Datos"
INSERT INTO lecciones (curso_id, title, type, content)
VALUES
(6, 'Introducción a Bases de Datos', 'text', 'Conceptos básicos sobre bases de datos...'),
(6, 'SQL: Sintaxis Básica', 'video', 'Video de introducción a la sintaxis SQL...'),
(6, 'Operaciones CRUD en SQL', 'text', 'Explicación de operaciones CRUD...'),
(6, 'Consultas y Filtros', 'video', 'Uso de consultas y filtros en SQL...'),
(6, 'Funciones Agregadas', 'text', 'Lección sobre funciones agregadas en SQL...'),
(6, 'Joins y Relaciones', 'video', 'Lección sobre joins en SQL...'),
(6, 'Subconsultas en SQL', 'text', 'Explicación de subconsultas...'),
(6, 'Índices y Rendimiento', 'video', 'Uso de índices para mejorar rendimiento...'),
(6, 'Seguridad en Bases de Datos', 'text', 'Buenas prácticas de seguridad...'),
(6, 'Proyecto Final: Análisis de Ventas', 'video', 'Proyecto final sobre análisis de ventas...');
