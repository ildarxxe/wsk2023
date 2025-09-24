const { Question } = require("./Question");

const mockText = "What is the capital of France?";
const mockChoices = ["London", "Paris", "Berlin"];
const mockStrategy = {
    correctAnswers: [2],
    checkAnswer: (answers) => answers.includes(2),
    maxAnswers: 1,
};

describe("Question", () => {
    let question;

    beforeEach(() => {
        question = new Question(mockText, mockChoices, mockStrategy);
    });

    it("should correctly assign the text, choices, and strategy to the instance properties", () => {
        expect(question.text).toBe(mockText);
        expect(question.choices).toEqual(mockChoices);
        expect(question.strategy).toEqual(mockStrategy);
    });

    it("should correctly execute the checkAnswer function from the strategy", () => {
        const isCorrect = question.strategy.checkAnswer([2]);
        expect(isCorrect).toBe(true);

        const isIncorrect = question.strategy.checkAnswer([1]);
        expect(isIncorrect).toBe(false);
    });
});
