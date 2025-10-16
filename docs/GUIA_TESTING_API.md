#!/bin/bash

# Configuración
API_URL="http://localhost:8000/api"
PUBLIC_URL="$API_URL/public"

echo "=================================="
echo "  TESTS MANUALES DE API - FILTROS"
echo "=================================="
echo ""

# Colores
GREEN='\033[0;32m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# 1. CURSOS
echo -e "${BLUE}[1/10] Testeando Cursos Públicos...${NC}"
echo "- Todos los cursos:"
curl -s "$PUBLIC_URL/courses" | head -n 20
echo ""
echo "- Filtrados por año de ingreso 2024:"
curl -s "$PUBLIC_URL/courses?anio_ingreso=2024" | head -n 20
echo ""
echo "- Años disponibles:"
curl -s "$PUBLIC_URL/courses/years"
echo ""
echo ""

# 2. NOVEDADES
echo -e "${BLUE}[2/10] Testeando Novedades Públicas...${NC}"
echo "- Todas las novedades activas:"
curl -s "$PUBLIC_URL/novedades" | head -n 20
echo ""
echo "- Filtradas por búsqueda 'evento':"
curl -s "$PUBLIC_URL/novedades?search=evento" | head -n 20
echo ""
echo ""

# 3. INFORMES
echo -e "${BLUE}[3/10] Testeando Informes Públicos...${NC}"
echo "- Todos los informes:"
curl -s "$PUBLIC_URL/informes" | head -n 20
echo ""
echo "- Filtrados por búsqueda:"
curl -s "$PUBLIC_URL/informes?search=calendario" | head -n 20
echo ""
echo ""

# 4. SALAS
echo -e "${BLUE}[4/10] Testeando Salas Públicas...${NC}"
echo "- Todas las salas:"
curl -s "$PUBLIC_URL/rooms" | head -n 20
echo ""
echo ""

# 5. STAFF
echo -e "${BLUE}[5/10] Testeando Staff Público...${NC}"
echo "- Todo el equipo:"
curl -s "$PUBLIC_URL/staff" | head -n 20
echo ""
echo ""

# 6. MAGISTERS
echo -e "${BLUE}[6/10] Testeando Magísteres Públicos...${NC}"
echo "- Todos los magísteres:"
curl -s "$PUBLIC_URL/magisters" | head -n 20
echo ""
echo "- Con contador de cursos:"
curl -s "$PUBLIC_URL/magisters-with-course-count" | head -n 20
echo ""
echo ""

# 7. CLASES
echo -e "${BLUE}[7/10] Testeando Clases Públicas...${NC}"
echo "- Todas las clases:"
curl -s "$PUBLIC_URL/clases" | head -n 20
echo ""
echo ""

# 8. EVENTOS
echo -e "${BLUE}[8/10] Testeando Eventos Públicos...${NC}"
echo "- Eventos en rango de fechas:"
curl -s "$PUBLIC_URL/events?start=2024-01-01&end=2024-12-31" | head -n 20
echo ""
echo ""

# 9. PERÍODOS PÚBLICOS
echo -e "${BLUE}[9/10] Testeando Períodos...${NC}"
echo "- Trimestre siguiente:"
curl -s "$API_URL/trimestre-siguiente?fecha=2024-01-15"
echo ""
echo "- Período por fecha:"
curl -s "$API_URL/periodo-por-fecha?fecha=2024-03-15"
echo ""
echo ""

# 10. EMERGENCIAS
echo -e "${BLUE}[10/10] Testeando Emergencias Públicas...${NC}"
echo "- Emergencia activa:"
curl -s "$PUBLIC_URL/emergencies/active"
echo ""
echo ""

echo -e "${GREEN}=================================="
echo "  TESTS COMPLETADOS"
echo "==================================${NC}"

