import os
import re

models_dir = r"d:\SinergiEdu\app\Models"
models_to_update = [
    'User.php', 'Teacher.php', 'Student.php', 'StudentParent.php', 
    'Waka.php', 'Pengawas.php', 'KepalaSekolah.php',
    'Classroom.php', 'Subject.php', 'AcademicYear.php', 'Semester.php',
    'TeacherSubject.php', 'StudentClass.php', 'Setting.php'
]

trait_statement = "    use \\App\\Traits\\TenantScoped;\n"

for model_file in models_to_update:
    path = os.path.join(models_dir, model_file)
    if not os.path.exists(path):
        print(f"Not found: {model_file}")
        continue
        
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Check if already added
    if 'use \App\Traits\TenantScoped;' in content:
        print(f"Already scoped: {model_file}")
        continue

    # Find the class declaration and opening brace
    match = re.search(r'(class\s+\w+[\s\w]*\n{)', content)
    if match:
        insert_pos = match.end()
        # insert right after the opening brace
        content = content[:insert_pos] + '\n' + trait_statement + content[insert_pos:]
        
        with open(path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Added trait to {model_file}")
    else:
        print(f"Could not find class opening brace in {model_file}")
