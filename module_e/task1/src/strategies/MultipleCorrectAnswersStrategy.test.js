const {
    MultipleCorrectAnswersStrategy,
} = require("./MultipleCorrectAnswersStrategy");

describe("MultipleCorrectAnswersStrategy", () => {
    const correctAnswers = [2,3]
    const mcas = new MultipleCorrectAnswersStrategy(correctAnswers)

    it("user answer is correct", () => {
        const userAnswers = [2]
        const result = mcas.checkAnswer(userAnswers)
        expect(result).toBe(true)
    })

    it("user answer is incorrect, too many answers", () => {
        const userAnswers = [2, 4]
        const result = mcas.checkAnswer(userAnswers)
        expect(result).toBe(false)
    })

    it("user answer is incorrect, mistake in answer", () => {
        const userAnswers = [5]
        const result = mcas.checkAnswer(userAnswers)
        expect(result).toBe(false)
    })
});
