#!/bin/bash

# Script para convertir imágenes a formato WebP
# Mejora el rendimiento del sitio web de Szystems

echo "🚀 Iniciando conversión de imágenes a WebP..."

# Verificar si cwebp está instalado
if ! command -v cwebp &> /dev/null; then
    echo "❌ Error: cwebp no está instalado."
    echo "💡 Para instalar en Windows, descarga las herramientas WebP de:"
    echo "   https://developers.google.com/speed/webp/download"
    echo ""
    echo "💡 Para instalar en Ubuntu/Debian:"
    echo "   sudo apt-get install webp"
    echo ""
    echo "💡 Para instalar en macOS:"
    echo "   brew install webp"
    exit 1
fi

# Crear directorio de salida si no existe
mkdir -p "assets/img/webp"

# Contador de archivos procesados
processed=0
errors=0

# Función para convertir imagen
convert_image() {
    local input_file="$1"
    local output_file="$2"
    local quality="$3"
    
    echo "🔄 Convirtiendo: $(basename "$input_file")"
    
    if cwebp -q "$quality" "$input_file" -o "$output_file" 2>/dev/null; then
        # Obtener tamaños de archivo
        original_size=$(stat -f%z "$input_file" 2>/dev/null || stat -c%s "$input_file" 2>/dev/null)
        webp_size=$(stat -f%z "$output_file" 2>/dev/null || stat -c%s "$output_file" 2>/dev/null)
        
        if [ "$original_size" -gt 0 ] && [ "$webp_size" -gt 0 ]; then
            reduction=$(( (original_size - webp_size) * 100 / original_size ))
            echo "✅ $(basename "$output_file") - Reducción: ${reduction}%"
        else
            echo "✅ $(basename "$output_file") - Convertido exitosamente"
        fi
        
        ((processed++))
    else
        echo "❌ Error convirtiendo: $(basename "$input_file")"
        ((errors++))
    fi
}

echo "📁 Procesando imágenes en assets/img/..."

# Convertir imágenes principales con alta calidad (90%)
for img in assets/img/*.{jpg,jpeg,png,JPG,JPEG,PNG}; do
    if [ -f "$img" ]; then
        filename=$(basename "$img")
        name_without_ext="${filename%.*}"
        webp_file="assets/img/${name_without_ext}.webp"
        
        convert_image "$img" "$webp_file" 90
    fi
done

# Convertir imágenes de clientes con calidad media (80%)
echo "📁 Procesando imágenes de clientes..."
for img in assets/img/clients/*.{jpg,jpeg,png,JPG,JPEG,PNG}; do
    if [ -f "$img" ]; then
        filename=$(basename "$img")
        name_without_ext="${filename%.*}"
        webp_file="assets/img/clients/${name_without_ext}.webp"
        
        convert_image "$img" "$webp_file" 80
    fi
done

# Convertir imágenes de portfolio con calidad alta (85%)
echo "📁 Procesando imágenes de portfolio..."
for img in assets/img/portfolio/*.{jpg,jpeg,png,JPG,JPEG,PNG}; do
    if [ -f "$img" ]; then
        filename=$(basename "$img")
        name_without_ext="${filename%.*}"
        webp_file="assets/img/portfolio/${name_without_ext}.webp"
        
        convert_image "$img" "$webp_file" 85
    fi
done

# Convertir imágenes de blog con calidad media (75%)
echo "📁 Procesando imágenes de blog..."
for img in assets/img/blog/*.{jpg,jpeg,png,JPG,JPEG,PNG}; do
    if [ -f "$img" ]; then
        filename=$(basename "$img")
        name_without_ext="${filename%.*}"
        webp_file="assets/img/blog/${name_without_ext}.webp"
        
        convert_image "$img" "$webp_file" 75
    fi
done

echo ""
echo "📊 Resumen de conversión:"
echo "✅ Archivos procesados: $processed"
echo "❌ Errores: $errors"

if [ $processed -gt 0 ]; then
    echo ""
    echo "🎉 ¡Conversión completada!"
    echo "💡 Las imágenes WebP se cargarán automáticamente en navegadores compatibles"
    echo "💡 Las imágenes originales se mantendrán como fallback"
    echo ""
    echo "📋 Próximos pasos recomendados:"
    echo "1. Subir las nuevas imágenes .webp al servidor"
    echo "2. Verificar que el archivo .htaccess esté configurado correctamente"
    echo "3. Probar el sitio en diferentes navegadores"
    echo "4. Medir la mejora de velocidad con PageSpeed Insights"
else
    echo ""
    echo "⚠️  No se procesaron archivos. Verifica que existan imágenes en las carpetas especificadas."
fi

echo ""
echo "🔗 Herramientas útiles para medir el rendimiento:"
echo "   • Google PageSpeed Insights: https://pagespeed.web.dev/"
echo "   • GTmetrix: https://gtmetrix.com/"
echo "   • WebPageTest: https://www.webpagetest.org/"
