use App\Http\Controllers\QuizController;

Route::post('/quiz',[QuizController::class,'createQuiz']);
Route::post('/quiz/attempt',[QuizController::class,'submitAttempt']);
Route::get('/quiz/{id}',[QuizController::class,'getResult']);