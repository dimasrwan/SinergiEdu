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

# Common fixes
common_replacements = [
    # Card styles
    (r'\brounded-3xl\b', 'rounded-2xl'),
    (r'\bshadow-md\b', 'shadow-sm'),
    (r'\bshadow-cyan\b', 'shadow-sm'),
    (r'\bshadow-red\b', 'shadow-sm'),
    (r'\bbg-primary-dark\b', 'bg-blue-700'),
    (r'\btext-primary-hover\b', 'text-blue-700'),
    (r'\bbg-primary-hover\b', 'bg-blue-700'),
    
    # Fonts
    (r'\btext-4xl\b', 'text-2xl'),
    (r'\btext-3xl\b', 'text-2xl'),
    
    # Colors
    (r'\bbg-cyan-(\d+)\b', r'bg-blue-\1'),
    (r'\bborder-cyan-(\d+)\b', r'border-blue-\1'),
    (r'\btext-cyan-(\d+)\b', r'text-blue-\1'),
    
    (r'\bbg-violet-(\d+)\b', r'bg-blue-\1'),
    (r'\bborder-violet-(\d+)\b', r'border-blue-\1'),
    (r'\btext-violet-(\d+)\b', r'text-blue-\1'),
    
    (r'\bbg-indigo-(\d+)\b', r'bg-blue-\1'),
    (r'\bborder-indigo-(\d+)\b', r'border-blue-\1'),
    (r'\btext-indigo-(\d+)\b', r'text-blue-\1'),
]

pengawas_dir = 'resources/views/pages/pengawas'
process_directory(pengawas_dir, common_replacements)

kepala_sekolah_dir = 'resources/views/pages/kepala-sekolah'
process_directory(kepala_sekolah_dir, common_replacements + [
    # Specific to kepala-sekolah
    (r'\bpx-4\s+py-3\b', 'p-6'),
    (r'\bpy-3\s+px-4\b', 'p-6'),
])

waka_dir = 'resources/views/pages/waka'
process_directory(waka_dir, common_replacements)

print("Done.")
