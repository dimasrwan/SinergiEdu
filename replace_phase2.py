import os
import re

def process_directory(directory_path):
    for root, _, files in os.walk(directory_path):
        for file in files:
            if file.endswith('.blade.php'):
                file_path = os.path.join(root, file)
                with open(file_path, 'r', encoding='utf-8') as f:
                    content = f.read()
                
                original_content = content
                
                # Standardize buttons
                # Any rounded or rounded-md on <button> or <a> with inline-flex should be rounded-lg
                content = re.sub(r'(\b(?:inline-flex|flex|button)\b[^>]*)\brounded(-md|-xl|-2xl|-full)?\b', r'\1rounded-lg', content)
                
                # Standardize inputs
                content = re.sub(r'(<input[^>]*)\brounded(-md|-xl|-2xl|-full)?\b', r'\1rounded-lg', content)
                content = re.sub(r'(<select[^>]*)\brounded(-md|-xl|-2xl|-full)?\b', r'\1rounded-lg', content)
                content = re.sub(r'(<textarea[^>]*)\brounded(-md|-xl|-2xl|-full)?\b', r'\1rounded-lg', content)
                
                # Modal standard (bg-white rounded-2xl)
                content = re.sub(r'(<div[^>]*x-show="show"[^>]*)\brounded(-lg|-xl|-3xl|-md)?\b', r'\1rounded-2xl', content)
                
                # Semantic Badges
                # Success
                content = re.sub(r'\b(bg-green-100|bg-green-50)\b(?=[^>]*>(Aktif|Selesai|Berhasil|Disetujui))', r'bg-emerald-50', content, flags=re.IGNORECASE)
                content = re.sub(r'\b(text-green-700|text-green-800)\b(?=[^>]*>(Aktif|Selesai|Berhasil|Disetujui))', r'text-emerald-700', content, flags=re.IGNORECASE)
                
                # Warning
                content = re.sub(r'\b(bg-yellow-100|bg-orange-100|bg-yellow-50|bg-orange-50)\b(?=[^>]*>(Pending|Menunggu|Proses|Diproses|Draft))', r'bg-amber-50', content, flags=re.IGNORECASE)
                content = re.sub(r'\b(text-yellow-700|text-yellow-800|text-orange-700|text-orange-800)\b(?=[^>]*>(Pending|Menunggu|Proses|Diproses|Draft))', r'text-amber-700', content, flags=re.IGNORECASE)
                
                # Neutral (Administrative inactive)
                content = re.sub(r'\b(bg-red-100|bg-red-50)\b(?=[^>]*>(Nonaktif))', r'bg-slate-50', content, flags=re.IGNORECASE)
                content = re.sub(r'\b(text-red-700|text-red-800)\b(?=[^>]*>(Nonaktif))', r'text-slate-700', content, flags=re.IGNORECASE)
                
                if content != original_content:
                    with open(file_path, 'w', encoding='utf-8') as f:
                        f.write(content)
                    print(f"Updated {file_path}")

print("Starting detail UI consistency replacement...")
directories = [
    'resources/views/pages',
    'resources/views/components',
    'resources/views/profile'
]
for d in directories:
    if os.path.exists(d):
        process_directory(d)
print("Done.")
