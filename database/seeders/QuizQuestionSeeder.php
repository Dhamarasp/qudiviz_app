<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;

class QuizQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get English language
        $english = Language::where('code', 'en')->first();

        if (!$english) {
            $this->command->info('English language not found. Skipping quiz questions seeding.');
            return;
        }

        // Create grammar questions
        $this->createGrammarQuestions($english);
        
        // Create vocabulary questions
        $this->createVocabularyQuestions($english);
    }

    /**
     * Create grammar questions for English
     */
    private function createGrammarQuestions($language)
    {
        $questions = [
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'Which sentence is in the present continuous tense?',
                'difficulty_level' => 'beginner',
                'hint' => 'The present continuous tense describes actions happening right now.',
                'explanation' => 'The present continuous tense is formed with "am/is/are" + verb + "-ing".',
                'category' => 'grammar',
                'answers' => [
                    [
                        'answer_text' => 'She reads a book.',
                        'is_correct' => false,
                        'explanation' => 'This is in the simple present tense.',
                    ],
                    [
                        'answer_text' => 'She read a book.',
                        'is_correct' => false,
                        'explanation' => 'This is in the simple past tense.',
                    ],
                    [
                        'answer_text' => 'She is reading a book.',
                        'is_correct' => true,
                        'explanation' => 'This sentence uses "is" + "reading" (verb + -ing), which is the present continuous form.',
                    ],
                    [
                        'answer_text' => 'She has read a book.',
                        'is_correct' => false,
                        'explanation' => 'This is in the present perfect tense.',
                    ],
                ],
            ],
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'Choose the correct form of the verb for the sentence: "They _____ to the store yesterday."',
                'difficulty_level' => 'beginner',
                'hint' => 'This sentence is in the past tense.',
                'explanation' => 'For regular actions in the past, we use the simple past tense.',
                'category' => 'grammar',
                'answers' => [
                    [
                        'answer_text' => 'go',
                        'is_correct' => false,
                        'explanation' => 'This is the present tense form.',
                    ],
                    [
                        'answer_text' => 'going',
                        'is_correct' => false,
                        'explanation' => 'This is the present participle form.',
                    ],
                    [
                        'answer_text' => 'goes',
                        'is_correct' => false,
                        'explanation' => 'This is the third-person singular present tense form.',
                    ],
                                        [
                        'answer_text' => 'went',
                        'is_correct' => true,
                        'explanation' => '"Went" is the past tense of "go".',
                    ],
                ],
            ],
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'Which sentence contains a preposition?',
                'difficulty_level' => 'beginner',
                'hint' => 'Prepositions show relationships between words, often indicating location or time.',
                'explanation' => 'Common prepositions include "in," "on," "at," "by," "with," etc.',
                'category' => 'grammar',
                'answers' => [
                    [
                        'answer_text' => 'She runs quickly.',
                        'is_correct' => false,
                        'explanation' => 'This sentence contains an adverb (quickly) but no preposition.',
                    ],
                                        [
                        'answer_text' => 'The book is on the table.',
                        'is_correct' => true,
                        'explanation' => '"On" is a preposition showing the relationship between the book and the table.',
                    ],
                    [
                        'answer_text' => 'They are singing.',
                        'is_correct' => false,
                        'explanation' => 'This sentence does not contain a preposition.',
                    ],
                    [
                        'answer_text' => 'The red car is fast.',
                        'is_correct' => false,
                        'explanation' => 'This sentence contains an adjective (red) but no preposition.',
                    ],
                ],
            ],
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'Which of the following is a correct use of the article "an"?',
                'difficulty_level' => 'beginner',
                'hint' => 'We use "an" before words that start with a vowel sound.',
                'explanation' => 'The article "an" is used before words that begin with a vowel sound, not necessarily a vowel letter.',
                'category' => 'grammar',
                'answers' => [
                    [
                        'answer_text' => 'an house',
                        'is_correct' => false,
                        'explanation' => '"House" starts with a consonant sound, so it should be "a house."',
                    ],
                                        [
                        'answer_text' => 'an hour',
                        'is_correct' => true,
                        'explanation' => 'Although "hour" starts with "h," the "h" is silent, so it begins with a vowel sound.',
                    ],
                    [
                        'answer_text' => 'an university',
                        'is_correct' => false,
                        'explanation' => '"University" starts with a consonant sound (like "you"), so it should be "a university."',
                    ],
                    [
                        'answer_text' => 'an dog',
                        'is_correct' => false,
                        'explanation' => '"Dog" starts with a consonant sound, so it should be "a dog."',
                    ],
                ],
            ],
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'Which sentence uses the correct comparative form?',
                'difficulty_level' => 'intermediate',
                'hint' => 'Comparatives are used to compare two things.',
                'explanation' => 'For short adjectives, we usually add "-er" (e.g., "bigger"). For longer adjectives, we use "more" (e.g., "more beautiful").',
                'category' => 'grammar',
                'answers' => [
                    [
                        'answer_text' => 'This book is interestinger than that one.',
                        'is_correct' => false,
                        'explanation' => 'We don\'t add "-er" to longer adjectives like "interesting."',
                    ],
                                        [
                        'answer_text' => 'This book is more interesting than that one.',
                        'is_correct' => true,
                        'explanation' => 'For longer adjectives like "interesting," we use "more" to form the comparative.',
                    ],
                    [
                        'answer_text' => 'This book is more better than that one.',
                        'is_correct' => false,
                        'explanation' => 'This is a double comparative. "Better" is already the comparative form of "good."',
                    ],
                    [
                        'answer_text' => 'This book is more bigger than that one.',
                        'is_correct' => false,
                        'explanation' => 'This is a double comparative. "Bigger" is already the comparative form of "big."',
                    ],
                ],
            ],
        ];

        foreach ($questions as $questionData) {
            $answers = $questionData['answers'];
            unset($questionData['answers']);
            
            $questionData['language_id'] = $language->id;
            $question = QuizQuestion::create($questionData);
            
            foreach ($answers as $answerData) {
                $question->answers()->create($answerData);
            }
        }
    }

    /**
     * Create vocabulary questions for English
     */
    private function createVocabularyQuestions($language)
    {
        $questions = [
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'What is the meaning of the word "ubiquitous"?',
                'difficulty_level' => 'advanced',
                'hint' => 'This word describes something that seems to be everywhere.',
                'explanation' => '"Ubiquitous" means present, appearing, or found everywhere.',
                'category' => 'vocabulary',
                'answers' => [
                    [
                        'answer_text' => 'Rare or uncommon',
                        'is_correct' => false,
                        'explanation' => 'This is the opposite of "ubiquitous."',
                    ],
                    [
                        'answer_text' => 'Unique or one-of-a-kind',
                        'is_correct' => false,
                        'explanation' => 'This is not the meaning of "ubiquitous."',
                    ],
                    [
                        'answer_text' => 'Unclear or ambiguous',
                        'is_correct' => false,
                        'explanation' => 'This is not the meaning of "ubiquitous."',
                    ],
                                        [
                        'answer_text' => 'Present or found everywhere',
                        'is_correct' => true,
                        'explanation' => 'This is the correct definition of "ubiquitous."',
                    ],
                ],
            ],
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'Choose the correct synonym for "happy":',
                'difficulty_level' => 'beginner',
                'hint' => 'A synonym is a word with the same or similar meaning.',
                'explanation' => 'Synonyms for "happy" include "joyful," "glad," "cheerful," and "content."',
                'category' => 'vocabulary',
                'answers' => [
                    [
                        'answer_text' => 'Joyful',
                        'is_correct' => true,
                        'explanation' => '"Joyful" means feeling or showing great happiness, which is synonymous with "happy."',
                    ],
                    [
                        'answer_text' => 'Sad',
                        'is_correct' => false,
                        'explanation' => '"Sad" is an antonym (opposite) of "happy."',
                    ],
                    [
                        'answer_text' => 'Angry',
                        'is_correct' => false,
                        'explanation' => '"Angry" is not a synonym for "happy."',
                    ],
                    [
                        'answer_text' => 'Tired',
                        'is_correct' => false,
                        'explanation' => '"Tired" is not a synonym for "happy."',
                    ],
                ],
            ],
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'What is the meaning of the idiom "break the ice"?',
                'difficulty_level' => 'intermediate',
                'hint' => 'This idiom is about social situations.',
                'explanation' => 'Idioms are expressions that have a meaning different from the literal meaning of the words.',
                'category' => 'vocabulary',
                'answers' => [
                    [
                        'answer_text' => 'To do or say something to relieve tension or get conversation going in a social situation',
                        'is_correct' => true,
                        'explanation' => 'This is the correct meaning of the idiom "break the ice."',
                    ],
                    [
                        'answer_text' => 'To literally break a frozen surface',
                        'is_correct' => false,
                        'explanation' => 'This is the literal meaning, not the idiomatic meaning.',
                    ],
                    [
                        'answer_text' => 'To end a friendship',
                        'is_correct' => false,
                        'explanation' => 'This is not the meaning of "break the ice."',
                    ],
                    [
                        'answer_text' => 'To solve a difficult problem',
                        'is_correct' => false,
                        'explanation' => 'This is not the meaning of "break the ice."',
                    ],
                ],
            ],
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'Choose the word that best completes the sentence: "The chef _____ the ingredients before cooking."',
                'difficulty_level' => 'intermediate',
                'hint' => 'This word describes preparing ingredients by cutting them into small pieces.',
                'explanation' => 'Different cooking verbs describe specific actions in the kitchen.',
                'category' => 'vocabulary',
                'answers' => [
                    [
                        'answer_text' => 'boiled',
                        'is_correct' => false,
                        'explanation' => '"Boiled" means cooked in boiling water, not preparing ingredients before cooking.',
                    ],
                    [
                        'answer_text' => 'baked',
                        'is_correct' => false,
                        'explanation' => '"Baked" means cooked by dry heat in an oven, not preparing ingredients before cooking.',
                    ],
                                        [
                        'answer_text' => 'chopped',
                        'is_correct' => true,
                        'explanation' => '"Chopped" means cut into small pieces, which is a common preparation step in cooking.',
                    ],
                    [
                        'answer_text' => 'served',
                        'is_correct' => false,
                        'explanation' => '"Served" means presented food to someone, which happens after cooking, not before.',
                    ],
                ],
            ],
            [
                'question_type' => 'multiple_choice',
                'question_text' => 'What is the meaning of the word "procrastinate"?',
                'difficulty_level' => 'intermediate',
                'hint' => 'This word describes a common behavior related to tasks and deadlines.',
                'explanation' => '"Procrastinate" is a verb that describes delaying or postponing action.',
                'category' => 'vocabulary',
                'answers' => [
                    [
                        'answer_text' => 'To delay or postpone action',
                        'is_correct' => true,
                        'explanation' => 'This is the correct definition of "procrastinate."',
                    ],
                    [
                        'answer_text' => 'To work quickly and efficiently',
                        'is_correct' => false,
                        'explanation' => 'This is the opposite of "procrastinate."',
                    ],
                    [
                        'answer_text' => 'To plan ahead carefully',
                        'is_correct' => false,
                        'explanation' => 'This is not the meaning of "procrastinate."',
                    ],
                    [
                        'answer_text' => 'To delegate tasks to others',
                        'is_correct' => false,
                        'explanation' => 'This is not the meaning of "procrastinate."',
                    ],
                ],
            ],
        ];

        foreach ($questions as $questionData) {
            $answers = $questionData['answers'];
            unset($questionData['answers']);
            
            $questionData['language_id'] = $language->id;
            $question = QuizQuestion::create($questionData);
            
            foreach ($answers as $answerData) {
                $question->answers()->create($answerData);
            }
        }
    }
}
