<form method="POST" action="/api/attempt">
    <input type="hidden" name="quiz_id" value="1">

    <p>2 + 2 = ?</p>
    <input type="text" name="answers[0][answer_text]">
    <input type="hidden" name="answers[0][question_id]" value="1">

    <button type="submit">Submit</button>
</form>