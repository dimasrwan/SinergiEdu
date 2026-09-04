import os
import re
from collections import defaultdict
import json

roles = [
    'super-admin', 'admin', 'guru', 'siswa', 
    'orangtua', 'waka', 'pengawas', 'kepala-sekolah'
]

views_dir = 'resources/views/pages'
components_dir = 'resources/views/components'

data = {
    'colors': defaultdict(lambda: defaultdict(int)),
    'padding': defaultdict(lambda: defaultdict(int)),
    'rounded': defaultdict(lambda: defaultdict(int)),
    'shadow': defaultdict(lambda: defaultdict(int)),
    'text_size': defaultdict(lambda: defaultdict(int)),
    'components': defaultdict(lambda: defaultdict(int))
}

patterns = {
    'colors': r'\b(bg|text|border)-(slate|gray|zinc|neutral|stone|red|orange|amber|yellow|lime|green|emerald|teal|cyan|sky|blue|indigo|violet|purple|fuchsia|pink|rose|primary|secondary)-(\d{2,3}|[a-z]+)\b',
    'padding': r'\b(p|px|py|pt|pr|pb|pl)-(\d+|[a-z]+)\b',
    'rounded': r'\brounded(-[a-z2-3]+)?\b',
    'shadow': r'\bshadow(-[a-z]+)?\b',
    'text_size': r'\btext-(xs|sm|base|lg|xl|2xl|3xl|4xl|5xl|6xl)\b',
    'components': r'<x-([a-zA-Z0-9-]+)'
}

def scan_dir(role):
    path = os.path.join(views_dir, role)
    if not os.path.exists(path):
        return
        
    for root, _, files in os.walk(path):
        for file in files:
            if file.endswith('.blade.php'):
                filepath = os.path.join(root, file)
                with open(filepath, 'r', encoding='utf-8') as f:
                    content = f.read()
                    
                    for match in re.finditer(patterns['colors'], content):
                        data['colors'][role][match.group(0)] += 1
                        
                    for match in re.finditer(patterns['padding'], content):
                        data['padding'][role][match.group(0)] += 1
                        
                    for match in re.finditer(patterns['rounded'], content):
                        data['rounded'][role][match.group(0)] += 1
                        
                    for match in re.finditer(patterns['shadow'], content):
                        data['shadow'][role][match.group(0)] += 1
                        
                    for match in re.finditer(patterns['text_size'], content):
                        data['text_size'][role][match.group(0)] += 1
                        
                    for match in re.finditer(patterns['components'], content):
                        data['components'][role][match.group(1)] += 1

for role in roles:
    scan_dir(role)

# Format output
with open('audit_results.json', 'w') as f:
    json.dump(data, f, indent=2)

print("Scan complete. Results saved to audit_results.json")
