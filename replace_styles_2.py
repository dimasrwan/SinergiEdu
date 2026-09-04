import os
import re

def process_directory(directory_path, replacements):
    for root, _, files in os.walk(directory_path):
        for file in files:
            if file.endswith('.blade.php'):
                file_path = os.path.join(root, file)
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()

                original_content = content
                for pattern, repl in replacements:
                    content = re.sub(pattern, repl, content)

                if content != original_content:
                    with open(file_path, 'w', encoding='utf-8') as f:
                        f.write(content)
                    print(f"Updated {file_path}")

print("Starting replacement...")

# Common fixes for tables
common_replacements = [
    # Table cell paddings: often px-6 py-3 or similar, replace with px-4 py-4
    (r'\bpx-6\s+py-3\b', 'px-4 py-4'),
    (r'\bpy-3\s+px-6\b', 'px-4 py-4'),
    (r'\bpx-3\s+py-3\b', 'px-4 py-4'),
    (r'\bpy-3\s+px-3\b', 'px-4 py-4'),
    (r'\bpx-5\s+py-3\b', 'px-4 py-4'),
    (r'\bpx-4\s+py-3\b(?!\s+(?:sm|md|lg|xl))', 'px-4 py-4'), # Only if it's not responsive
    (r'\bpy-3\s+px-4\b', 'px-4 py-4'),
    
    # Text sizes in Waka/Pengawas for tables
    (r'\btext-sm\b\s+font-medium\s+text-gray-900', 'text-sm font-semibold text-slate-900'),
    (r'\btext-sm\b\s+text-gray-500', 'text-sm text-slate-500'),
    
    # Some buttons use bg-primary-dark, change to blue
    (r'\bbg-primary-dark\b', 'bg-blue-600'),
    
    # Additional arbitrary colors
    (r'\btext-indigo-200\b', 'text-blue-200'),
    (r'\btext-indigo-500\b', 'text-blue-500'),
    (r'\bbg-orange-500\b', 'bg-amber-500'),
    (r'\bborder-orange-100\b', 'border-amber-100'),
    (r'\btext-orange-700\b', 'text-amber-700'),
    (r'\bbg-orange-200\b', 'bg-amber-200'),
    (r'\bbg-orange-50\b', 'bg-amber-50'),
]

directories = [
    'resources/views/pages/pengawas',
    'resources/views/pages/kepala-sekolah',
    'resources/views/pages/waka'
]

for d in directories:
    process_directory(d, common_replacements)

print("Done.")
