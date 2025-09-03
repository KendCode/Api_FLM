window.onload = function() {
  const ui = SwaggerUIBundle({
    url: "http://127.0.0.1:8000/api-docs.json", // JSON servido por Laravel
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
};
