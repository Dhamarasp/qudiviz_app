<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Lesson;
use App\Models\PracticeCategory;
use App\Models\QuizQuestion;
use App\Models\SubLesson;
use App\Models\SubLessonContent;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'languages' => Language::count(),
            'units' => Unit::count(),
            'lessons' => Lesson::count(),
            'subLessons' => SubLesson::count(),
            'quizQuestions' => QuizQuestion::count(),
            'practiceCategories' => PracticeCategory::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    // Users Management
    public function users()
    {
        $users = User::paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'is_admin' => 'boolean',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->has('is_admin');
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User berhasil diperbarui.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User berhasil dihapus.');
    }

    // Languages Management
    public function languages()
    {
        $languages = Language::paginate(15);
        return view('admin.languages.index', compact('languages'));
    }

    public function createLanguage()
    {
        return view('admin.languages.create');
    }

    public function storeLanguage(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code',
            'flag' => 'required|string|max:255',
        ]);

        Language::create($request->all());

        return redirect()->route('admin.languages')->with('success', 'Bahasa berhasil ditambahkan.');
    }

    public function editLanguage($id)
    {
        $language = Language::findOrFail($id);
        return view('admin.languages.edit', compact('language'));
    }

    public function updateLanguage(Request $request, $id)
    {
        $language = Language::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10|unique:languages,code,' . $id,
            'flag' => 'required|string|max:255',
        ]);

        $language->update($request->all());

        return redirect()->route('admin.languages')->with('success', 'Bahasa berhasil diperbarui.');
    }

    public function deleteLanguage($id)
    {
        $language = Language::findOrFail($id);
        $language->delete();

        return redirect()->route('admin.languages')->with('success', 'Bahasa berhasil dihapus.');
    }

    // Units Management
    public function units()
    {
        $units = Unit::with('language')->paginate(15);
        return view('admin.units.index', compact('units'));
    }

    public function createUnit()
    {
        $languages = Language::all();
        return view('admin.units.create', compact('languages'));
    }

    public function storeUnit(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'required|integer',
        ]);

        Unit::create($request->all());

        return redirect()->route('admin.units')->with('success', 'Unit berhasil ditambahkan.');
    }

    public function editUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $languages = Language::all();
        return view('admin.units.edit', compact('unit', 'languages'));
    }

    public function updateUnit(Request $request, $id)
    {
        $unit = Unit::findOrFail($id);

        $request->validate([
            'language_id' => 'required|exists:languages,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'required|integer',
        ]);

        $unit->update($request->all());

        return redirect()->route('admin.units')->with('success', 'Unit berhasil diperbarui.');
    }

    public function deleteUnit($id)
    {
        $unit = Unit::findOrFail($id);
        $unit->delete();

        return redirect()->route('admin.units')->with('success', 'Unit berhasil dihapus.');
    }

    // Lessons Management
    public function lessons()
    {
        $lessons = Lesson::with(['unit', 'unit.language'])->paginate(15);
        return view('admin.lessons.index', compact('lessons'));
    }

    public function createLesson()
    {
        $units = Unit::all();
        $languages = Language::all();
        return view('admin.lessons.create', compact('units', 'languages'));
    }

    public function storeLesson(Request $request)
    {
        // dd($request->all());

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'language_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'required|integer',
            'icon' => 'required|string|max:255',
        ]);

        Lesson::create($request->all());

        return redirect()->route('admin.lessons')->with('success', 'Pelajaran berhasil ditambahkan.');
    }

    public function editLesson($id)
    {
        $lesson = Lesson::findOrFail($id);
        $units = Unit::all();
        return view('admin.lessons.edit', compact('lesson', 'units'));
    }

    public function updateLesson(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'required|integer',
            'icon' => 'required|string|max:255',
        ]);

        $lesson->update($request->all());

        return redirect()->route('admin.lessons')->with('success', 'Pelajaran berhasil diperbarui.');
    }

    public function deleteLesson($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();

        return redirect()->route('admin.lessons')->with('success', 'Pelajaran berhasil dihapus.');
    }

    // SubLessons Management
    public function subLessons()
    {
        $subLessons = SubLesson::with(['lesson', 'lesson.unit'])->paginate(15);
        return view('admin.sub-lessons.index', compact('subLessons'));
    }

    public function createSubLesson()
    {
        $lessons = Lesson::all();
        return view('admin.sub-lessons.create', compact('lessons'));
    }

    public function storeSubLesson(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'required|integer',
        ]);

        SubLesson::create($request->all());

        return redirect()->route('admin.sub-lessons')->with('success', 'Sub Pelajaran berhasil ditambahkan.');
    }

    public function editSubLesson($id)
    {
        $subLesson = SubLesson::findOrFail($id);
        $lessons = Lesson::all();
        return view('admin.sub-lessons.edit', compact('subLesson', 'lessons'));
    }

    public function updateSubLesson(Request $request, $id)
    {
        $subLesson = SubLesson::findOrFail($id);

        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'order' => 'required|integer',
        ]);

        $subLesson->update($request->all());

        return redirect()->route('admin.sub-lessons')->with('success', 'Sub Pelajaran berhasil diperbarui.');
    }

    public function deleteSubLesson($id)
    {
        $subLesson = SubLesson::findOrFail($id);
        $subLesson->delete();

        return redirect()->route('admin.sub-lessons')->with('success', 'Sub Pelajaran berhasil dihapus.');
    }

    // Quiz Questions Management
    public function quizQuestions()
    {
        $quizQuestions = QuizQuestion::with(['lesson'])->paginate(15);
        return view('admin.quiz-questions.index', compact('quizQuestions'));
    }

    public function createQuizQuestion()
    {
        $lessons = Lesson::all();
        return view('admin.quiz-questions.create', compact('lessons'));
    }

    public function storeQuizQuestion(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:a,b,c,d',
        ]);

        QuizQuestion::create($request->all());

        return redirect()->route('admin.quiz-questions')->with('success', 'Pertanyaan Quiz berhasil ditambahkan.');
    }

    public function editQuizQuestion($id)
    {
        $quizQuestion = QuizQuestion::findOrFail($id);
        $lessons = Lesson::all();
        return view('admin.quiz-questions.edit', compact('quizQuestion', 'lessons'));
    }

    public function updateQuizQuestion(Request $request, $id)
    {
        $quizQuestion = QuizQuestion::findOrFail($id);

        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'correct_option' => 'required|in:a,b,c,d',
        ]);

        $quizQuestion->update($request->all());

        return redirect()->route('admin.quiz-questions')->with('success', 'Pertanyaan Quiz berhasil diperbarui.');
    }

    public function deleteQuizQuestion($id)
    {
        $quizQuestion = QuizQuestion::findOrFail($id);
        $quizQuestion->delete();

        return redirect()->route('admin.quiz-questions')->with('success', 'Pertanyaan Quiz berhasil dihapus.');
    }

    // Practice Categories Management
    public function practiceCategories()
    {
        $categories = PracticeCategory::paginate(15);
        return view('admin.practice-categories.index', compact('categories'));
    }

    public function createPracticeCategory()
    {
        return view('admin.practice-categories.create');
    }

    public function storePracticeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
        ]);

        PracticeCategory::create($request->all());

        return redirect()->route('admin.practice-categories')->with('success', 'Kategori Latihan berhasil ditambahkan.');
    }

    public function editPracticeCategory($id)
    {
        $category = PracticeCategory::findOrFail($id);
        return view('admin.practice-categories.edit', compact('category'));
    }

    public function updatePracticeCategory(Request $request, $id)
    {
        $category = PracticeCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        return redirect()->route('admin.practice-categories')->with('success', 'Kategori Latihan berhasil diperbarui.');
    }

    public function deletePracticeCategory($id)
    {
        $category = PracticeCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.practice-categories')->with('success', 'Kategori Latihan berhasil dihapus.');
    }

    // SubLesson Contents Management
    public function subLessonContents($subLessonId = null)
    {
        if ($subLessonId) {
            $subLesson = SubLesson::findOrFail($subLessonId);
            $contents = SubLessonContent::where('sub_lesson_id', $subLessonId)
                ->orderBy('order')
                ->paginate(15);
            return view('admin.sub-lesson-contents.index', compact('contents', 'subLesson'));
        } else {
            $contents = SubLessonContent::with('subLesson')
                ->orderBy('sub_lesson_id')
                ->orderBy('order')
                ->paginate(15);
            return view('admin.sub-lesson-contents.index', compact('contents'));
        }
    }

    public function createSubLessonContent($subLessonId = null)
    {
        $subLessons = SubLesson::all();
        $selectedSubLesson = $subLessonId ? SubLesson::findOrFail($subLessonId) : null;
        return view('admin.sub-lesson-contents.create', compact('subLessons', 'selectedSubLesson'));
    }

    public function storeSubLessonContent(Request $request)
    {
        $request->validate([
            'sub_lesson_id' => 'required|exists:sub_lessons,id',
            'content_type' => 'required|in:text,image,video,audio,exercise',
            'content' => 'required|string',
            'order' => 'required|integer',
        ]);

        SubLessonContent::create($request->all());

        return redirect()
            ->route('admin.sub-lesson-contents', ['subLessonId' => $request->sub_lesson_id])
            ->with('success', 'Konten Sub Pelajaran berhasil ditambahkan.');
    }

    public function editSubLessonContent($id)
    {
        $content = SubLessonContent::findOrFail($id);
        $subLessons = SubLesson::all();
        return view('admin.sub-lesson-contents.edit', compact('content', 'subLessons'));
    }

    public function updateSubLessonContent(Request $request, $id)
    {
        $content = SubLessonContent::findOrFail($id);
        
        $request->validate([
            'sub_lesson_id' => 'required|exists:sub_lessons,id',
            'content_type' => 'required|in:text,image,video,audio,exercise',
            'content' => 'required|string',
            'order' => 'required|integer',
        ]);

        $content->update($request->all());

        return redirect()
            ->route('admin.sub-lesson-contents', ['subLessonId' => $request->sub_lesson_id])
            ->with('success', 'Konten Sub Pelajaran berhasil diperbarui.');
    }

    public function deleteSubLessonContent($id)
    {
        $content = SubLessonContent::findOrFail($id);
        $subLessonId = $content->sub_lesson_id;
        $content->delete();

        return redirect()
            ->route('admin.sub-lesson-contents', ['subLessonId' => $subLessonId])
            ->with('success', 'Konten Sub Pelajaran berhasil dihapus.');
    }
}
