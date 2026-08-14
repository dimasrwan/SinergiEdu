import os
import glob

policies_dir = r"d:\SinergiEdu\app\Policies"
php_files = glob.glob(os.path.join(policies_dir, "*.php"))

for file_path in php_files:
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    if 'User::withoutGlobalScopes()' in content:
        content = content.replace('User::withoutGlobalScopes()->find', 'User::find')
        
        with open(file_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Updated {os.path.basename(file_path)}")
