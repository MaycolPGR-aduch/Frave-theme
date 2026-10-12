"""
Word cloud with the silhouette of Peru for the Experticia page (idea of Frave's management).

Writes assets/media/experticia/nube-peru.svg: the outline of Peru and the 56 agreed terms
as real SVG text (Lato, uppercase), placed so that none overlaps and all stay inside the
country, plus the Frave isotype in the centre. The page inlines the file, so the words take
the site's font and fade in with the section. nube-peru.json keeps the same terms for the
list screen readers get instead of the drawing.

    python bin/nube-peru.py

Change TERMS (or their tier) and run it again. The outline is Natural Earth's (public domain,
1:110m), as published in github.com/johan/world.geo.json. Widths are estimated from Lato
Bold's uppercase advances, slightly on the generous side so words never touch.
"""
import json
import math
import os
import random

PERU = [
    [-69.59, -17.58], [-69.858, -18.093], [-70.373, -18.348], [-71.375, -17.774], [-71.462, -17.363],
    [-73.445, -16.359], [-75.238, -15.266], [-76.009, -14.649], [-76.423, -13.823], [-76.259, -13.535],
    [-77.106, -12.223], [-78.092, -10.378], [-79.037, -8.387], [-79.446, -7.931], [-79.761, -7.194],
    [-80.537, -6.542], [-81.25, -6.137], [-80.926, -5.691], [-81.411, -4.737], [-81.1, -4.036],
    [-80.303, -3.405], [-80.184, -3.821], [-80.469, -4.059], [-80.442, -4.426], [-80.029, -4.346],
    [-79.625, -4.454], [-79.205, -4.959], [-78.64, -4.548], [-78.451, -3.873], [-77.838, -3.003],
    [-76.635, -2.609], [-75.545, -1.562], [-75.234, -0.911], [-75.373, -0.152], [-75.107, -0.057],
    [-74.442, -0.531], [-74.122, -1.003], [-73.66, -1.26], [-73.07, -2.309], [-72.326, -2.434],
    [-71.775, -2.17], [-71.414, -2.343], [-70.813, -2.257], [-70.048, -2.725], [-70.693, -3.743],
    [-70.394, -3.767], [-69.894, -4.298], [-70.795, -4.251], [-70.929, -4.402], [-71.748, -4.594],
    [-72.892, -5.275], [-72.965, -5.741], [-73.22, -6.089], [-73.12, -6.63], [-73.724, -6.919],
    [-73.723, -7.341], [-73.987, -7.524], [-73.571, -8.424], [-73.015, -9.033], [-73.227, -9.462],
    [-72.563, -9.52], [-72.185, -10.054], [-71.302, -10.079], [-70.482, -9.49], [-70.549, -11.009],
    [-70.094, -11.124], [-69.53, -10.952], [-68.665, -12.561], [-68.88, -12.9], [-68.929, -13.603],
    [-68.949, -14.454], [-69.34, -14.953], [-69.16, -15.324], [-69.39, -15.66], [-68.96, -16.501],
    [-69.59, -17.58],
]

# Tier 1 is the largest. Terms agreed with Frave's management (October 2026).
TERMS = {
    1: [
        'Consultoría especializada', 'Excelencia en calidad', 'Soluciones personalizadas',
        'Versatilidad de uso', 'Orientación técnica', 'Eficiencia superior',
    ],
    2: [
        'Desarrollo de fragancias para marcas exclusivas', 'Fragancias exclusivas y de autor',
        'Perfumería femenina y masculina', 'Durabilidad del desempeño',
        'Propuestas de alto valor y eficiencia', 'Sensaciones diferenciadoras', 'Evolución permanente',
        'Corrientes globales de innovación', 'Aplicación multifuncional', 'Persistencia prolongada',
        'Fragancias para tejidos', 'Velas con fragancia',
    ],
    3: [
        'Acondicionadores textiles', 'Alta eficacia neutralizadora de olores', 'Ambientadores sólidos decorativos',
        'Amplia compatibilidad de formulación', 'Aromatizadores de espacios', 'Biodegradabilidad',
        'Componentes de calidad controlada', 'Composiciones olfativas elaboradas', 'Desempeño estable',
        'Diseñadas para el cuidado de la piel', 'Emulsiones de cuidado facial y corporal',
        'Estabilidad en distintas formulaciones', 'Fragancias de concentración EDP y EDT',
        'Fragancias de uso diario', 'Lanzamientos especiales y ediciones de colección',
        'Limpiadores corporales en gel', 'Limpiadores para manos', 'Preferencias del consumidor',
        'Productos de aplicación industrial', 'Productos de hidratación corporal',
        'Productos de protección y cuidado después del sol', 'Productos para el bienestar animal',
        'Productos para el cuidado del baño', 'Productos para el cuidado del bebé',
        'Productos para el cuidado del cabello', 'Productos para el lavado de vajilla',
        'Productos para la desinfección', 'Productos para perfumar espacios',
        'Sensación de frescura persistente', 'Sistemas de difusión de fragancias',
        'Soluciones de limpieza corporal', 'Soluciones de limpieza versátil',
        'Soluciones de protección contra la transpiración', 'Soluciones para el lavado de prendas',
        'Soluciones para la limpieza de superficies', 'Tolerancia a variaciones de pH',
        'Marketing olfativo', 'Asesoría técnica',
    ],
}
BASE_SIZE = {1: 1.75, 2: 1.3, 3: 1.0}
TONES = {1: 'w1', 2: 'w2', 3: 'w3'}
WIDTH = 600
PAD = 12
GAP = 2.2
OUT = os.path.join(os.path.dirname(__file__), '..', 'assets', 'media', 'experticia', 'nube-peru.svg')

# --- Projection: equirectangular around Peru's middle latitude. ---
LAT0 = math.radians(-9.2)
raw = [(lon * math.cos(LAT0), -lat) for lon, lat in PERU]
min_x = min(p[0] for p in raw)
max_x = max(p[0] for p in raw)
min_y = min(p[1] for p in raw)
max_y = max(p[1] for p in raw)
scale = (WIDTH - 2 * PAD) / (max_x - min_x)
poly = [((x - min_x) * scale + PAD, (y - min_y) * scale + PAD) for x, y in raw]
HEIGHT = round((max_y - min_y) * scale + 2 * PAD)


def inside(px, py):
    hit = False
    j = len(poly) - 1
    for i in range(len(poly)):
        xi, yi = poly[i]
        xj, yj = poly[j]
        if (yi > py) != (yj > py) and px < (xj - xi) * (py - yi) / (yj - yi) + xi:
            hit = not hit
        j = i
    return hit


def edge_distance(px, py):
    best = float('inf')
    for i in range(len(poly) - 1):
        (ax, ay), (bx, by) = poly[i], poly[i + 1]
        dx, dy = bx - ax, by - ay
        t = max(0.0, min(1.0, ((px - ax) * dx + (py - ay) * dy) / (dx * dx + dy * dy)))
        best = min(best, math.hypot(px - ax - t * dx, py - ay - t * dy))
    return best


def rect_inside(x, y, w, h, margin=3.0):
    steps_x = max(2, int(w // 5) + 1)
    steps_y = max(2, int(h // 5) + 1)
    for i in range(steps_x + 1):
        for j in range(steps_y + 1):
            px, py = x + w * i / steps_x, y + h * j / steps_y
            if not inside(px, py):
                return False
    for px, py in ((x, y), (x + w, y), (x, y + h), (x + w, y + h)):
        if edge_distance(px, py) < margin:
            return False
    return True


def advance(text):
    width = 0.0
    for char in text.upper():
        if char == ' ':
            width += 0.27
        elif char in 'MW':
            width += 0.86
        elif char in 'IJ':
            width += 0.34
        elif char in '.,':
            width += 0.28
        else:
            width += 0.68
    return width + 0.02 * len(text)


def split(text):
    """One line up to 20 characters, two up to 36, three beyond: narrow blocks fit the shape."""
    words = text.split()
    parts = min(1 if len(text) <= 20 else 2 if len(text) <= 36 else 3, len(words))

    def options(rest, count):
        if count == 1:
            yield [' '.join(rest)]
            return
        for cut in range(1, len(rest) - count + 2):
            for tail in options(rest[cut:], count - 1):
                yield [' '.join(rest[:cut])] + tail

    return min(options(words, parts), key=lambda lines: max(len(line) for line in lines))


def block(text, size):
    lines = split(text)
    width = max(advance(line) for line in lines) * size
    height = size * (0.78 + 1.08 * (len(lines) - 1))
    return lines, width, height


# The isotype sits at the visual centre of the country (about 74.6° W, 8.6° S).
ISO_CX = (-74.6 * math.cos(LAT0) - min_x) * scale + PAD
ISO_CY = (8.6 - min_y) * scale + PAD
ISO_W = 46
ISO_H = ISO_W * 80 / 64
ISO_RECT = (ISO_CX - ISO_W / 2 - 6, ISO_CY - ISO_H / 2 - 6, ISO_W + 12, ISO_H + 12)

candidates = [(gx, gy) for gx in range(PAD, WIDTH - PAD, 3) for gy in range(PAD, HEIGHT - PAD, 3) if inside(gx, gy)]


def layout(unit):
    random.seed(7)
    placed = [ISO_RECT]
    words = []
    order = [(tier, term) for tier in (1, 2, 3) for term in TERMS[tier]]
    order.sort(key=lambda item: (item[0], -len(item[1])))
    for tier, term in order:
        size = BASE_SIZE[tier] * unit
        done = False
        while not done and size >= unit * 0.8:
            lines, w, h = block(term, size)
            if tier < 3:
                # Large words gather around the isotype, with some randomness.
                spots = sorted(candidates, key=lambda p: math.hypot(p[0] - ISO_CX, (p[1] - ISO_CY) * 0.7) + random.random() * 120)
            else:
                # Small words fill the rest from the top, row by row.
                spots = sorted(candidates, key=lambda p: (p[1] // 6, p[0] + random.random() * 4))
            for cx, cy in spots:
                x, y = cx - w / 2, cy - h / 2
                if any(x < px + pw + GAP and x + w + GAP > px and y < py + ph + GAP and y + h + GAP > py for px, py, pw, ph in placed):
                    continue
                if not rect_inside(x, y, w, h):
                    continue
                placed.append((x, y, w, h))
                words.append((term, lines, x, y, w, size, tier))
                done = True
                break
            if not done:
                size *= 0.92
        if not done:
            return None
    return words


low, high, result = 6.0, 20.0, None
for _ in range(10):
    unit = (low + high) / 2
    words = layout(unit)
    if words:
        low, result = unit, words
    else:
        high = unit
assert result, 'No layout fits: lower BASE_SIZE.'

path = 'M' + ' L'.join(f'{x:.1f} {y:.1f}' for x, y in poly) + ' Z'
iso_scale = ISO_W / 64
out = [
    f'<svg class="word-cloud__svg" viewBox="0 0 {WIDTH} {HEIGHT}" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">',
    f'<path class="word-cloud__shape" d="{path}"/>',
    f'<g class="word-cloud__isotype" transform="translate({ISO_CX - ISO_W / 2:.1f} {ISO_CY - ISO_H / 2:.1f}) scale({iso_scale:.3f})">'
    '<rect x="25" y="2" width="14" height="9" rx="2"/><circle cx="32" cy="6.5" r="1.6"/><path d="M28 11v7M36 11v7"/>'
    '<rect x="6" y="18" width="52" height="58" rx="6"/><path d="M14 27v39h10M50 27v39H40"/>'
    '<path d="M32 31c-6.2 7.6-9.4 13-9.4 17.6a9.4 9.4 0 0 0 18.8 0c0-4.6-3.2-10-9.4-17.6Z"/></g>',
]
result.sort(key=lambda word: math.hypot(word[2] + word[4] / 2 - ISO_CX, word[3] - ISO_CY))
for index, (term, lines, x, y, w, size, tier) in enumerate(result):
    cx = x + w / 2
    if len(lines) == 1:
        content = lines[0].upper().replace('&', '&amp;')
    else:
        content = ''.join(
            f'<tspan x="{cx:.1f}" dy="{0 if i == 0 else size * 1.08:.1f}">{line.upper()}</tspan>' for i, line in enumerate(lines)
        )
    out.append(
        f'<text class="{TONES[tier]}" style="--i:{index}" x="{cx:.1f}" y="{y + size * 0.78:.1f}" font-size="{size:.1f}">{content}</text>'
    )
out.append('</svg>')
os.makedirs(os.path.dirname(OUT), exist_ok=True)
with open(OUT, 'w', encoding='utf-8', newline='\n') as handle:
    handle.write('\n'.join(out) + '\n')
# The same terms, for the list screen readers get instead of the drawing.
with open(OUT.replace('.svg', '.json'), 'w', encoding='utf-8', newline='\n') as handle:
    json.dump([term for tier in (1, 2, 3) for term in TERMS[tier]], handle, ensure_ascii=False, indent=1)
    handle.write('\n')
print(f'{len(result)} words, unit {low:.2f}, viewBox {WIDTH}x{HEIGHT}')
