import os
import re

models_dir = r"d:\SinergiEdu\app\Models"
models_to_update = [
    'User.php', 'Teacher.php', 'Student.php', 'StudentParent.php', 
    'Waka.php', 'Pengawas.php', 'KepalaSekolah.php',
    'Classroom.php', 'Subject.php', 'AcademicYear.php', 'Semester.php',
    'TeacherSubject.php', 'StudentClass.php', 'Setting.php'
]

school_relation = r"""

    public function school(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(\App\Models\School::class);
    }
"""

for model_file in models_to_update:
    path = os.path.join(models_dir, model_file)
    if not os.path.exists(path):
        print(f"Not found: {model_file}")
        continue
        
    with open(path, 'r', encoding='utf-8') as f:
        content = f.read()

    # Add school_id to fillable
    if '#[Fillable([' in content:
        content = re.sub(r'#\[Fillable\(\[(.*?)\]\)\]', r"#[Fillable([\1, 'school_id'])]", content)
    elif 'protected $fillable = [' in content:
        content = re.sub(r'protected \$fillable = \[', r"protected $fillable = [\n        'school_id',", content)
    
    # Add belongsTo relation
    if 'public function school()' not in content:
        # insert before the last closing brace
        last_brace = content.rfind('}')
        if last_brace != -1:
            content = content[:last_brace] + school_relation + content[last_brace:]
            
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"Updated {model_file}")

# Now update School.php
school_path = os.path.join(models_dir, 'School.php')
if os.path.exists(school_path):
    with open(school_path, 'r', encoding='utf-8') as f:
        school_content = f.read()
    
    has_many_relations = r"""
    public function users(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\User::class);
    }
    
    public function teachers(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Teacher::class);
    }
    
    public function students(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Student::class);
    }
    
    public function studentParents(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\StudentParent::class);
    }
    
    public function wakas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Waka::class);
    }
    
    public function pengawas(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Pengawas::class);
    }
    
    public function kepalaSekolahs(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\KepalaSekolah::class);
    }
    
    public function classrooms(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Classroom::class);
    }
    
    public function subjects(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Subject::class);
    }
    
    public function academicYears(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\AcademicYear::class);
    }
    
    public function semesters(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Semester::class);
    }
    
    public function settings(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\Setting::class);
    }
"""
    # Replace placeholder or append before last brace
    if 'public function users(' not in school_content:
        # replace the commented out relationships if they exist
        import re
        school_content = re.sub(r'// Placeholder relationships.*', '', school_content, flags=re.DOTALL)
        school_content = school_content.replace('}', has_many_relations + '}', 1)
        if 'public function users(' not in school_content:
            last_brace = school_content.rfind('}')
            school_content = school_content[:last_brace] + has_many_relations + school_content[last_brace:]
            
    with open(school_path, 'w', encoding='utf-8') as f:
        f.write(school_content)
    print("Updated School.php")
