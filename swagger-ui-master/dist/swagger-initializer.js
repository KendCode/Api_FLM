window.onload = function() {
  // <editor-fold desc="Configuración de Swagger UI">
  const ui = SwaggerUIBundle({
    url: "api-docs.json", // tu archivo JSON
    dom_id: '#swagger-ui',
    deepLinking: true,
    presets: [
      SwaggerUIBundle.presets.apis,
      SwaggerUIStandalonePreset
    ],
    plugins: [
      SwaggerUIBundle.plugins.DownloadUrl
    ],
    layout: "BaseLayout"
  });
  window.ui = ui;
  // </editor-fold>
};
