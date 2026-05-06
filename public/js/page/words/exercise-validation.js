function compareAnswers() {
    const answerKey = getStoredData("answer_key_");
    const answerUser = getStoredData("answer_user_");    

    const fields = [
        "kalimat",
        "hukum",
        "kategori",
        "kedudukan",
        "irob",
        "tanda",
        "simbol",
    ];

    const result = [];

    answerKey.wordGroups.forEach((keyGroup) => {
        const userGroup = answerUser.wordGroups.find(
            (g) => String(g.id) === String(keyGroup.id),
        );
        if (!userGroup) return;

        keyGroup.words.forEach((keyWord) => {
            const userWord = userGroup.words.find(
                (w) => String(w.id) === String(keyWord.id),
            );
            
            if (!userWord) return;

            const fieldsResult = fields.map((field) => {
                const expected = String(keyWord[field] ?? "").trim();
                const actual = String(userWord[field] ?? "").trim();
                return {
                    field,
                    expected,
                    actual,
                    correct: expected === actual,
                };
            });
            result.push({
                wordId: keyWord.id,
                text: keyWord.text,
                correct: fieldsResult.every((f) => f.correct),
                fields: fieldsResult,
            });
        });
    });

    return result;
}
