<?php

namespace App\Http\Controllers;

use App\Helpers\ToastHelper;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    /**
     * Display the language selection page
     */
    public function index()
    {
        $languages = Language::where('status', 'active')->orderBy('is_popular', 'desc')->get();
        $currentLanguage = Auth::user()->learningLanguage;

        return view('switch-language.index', [
            'languages' => $languages,
            'currentLanguage' => $currentLanguage,
        ]);
    }

    /**
     * Switch the user's learning language
     */
    public function switchLanguage(Request $request)
    {
        $request->validate([
            'language_id' => 'required|exists:languages,id',
        ]);

        $user = Auth::user();
        $language = Language::findOrFail($request->language_id);

        $user->update([
            'learning_language_id' => $language->id,
        ]);

        ToastHelper::success("You are now learning {$language->name}!");

        return redirect()->route('lessons');
    }
}
