const { SingleAnswerStrategy } = require("./SingleAnswerStrategy");

describe("SingleAnswerStrategy", () => {
    const correctAnswers = [2]
    const sas = new SingleAnswerStrategy(correctAnswers);

    it("user answer is correct", () => {
        const userAnswers = [2]
        const res = sas.checkAnswer(userAnswers);
        expect(res).toBe(true);
    })
    it("user answer is incorrect, too many answers", () => {
        const userAnswers = [2, 3]
        const res = sas.checkAnswer(userAnswers);
        expect(res).toBe(false);
    })
    it("user answer is incorrect, mistake in answer", () => {
        const userAnswers = [4]
        const res = sas.checkAnswer(userAnswers);
        expect(res).toBe(false);
    })
    it("too many correct answers", () => {
        const createSAS = () => {
            new SingleAnswerStrategy([2, 3]);
        }
        expect(createSAS).toThrow()
        expect(createSAS).toThrow(`SingleAnswerStrategy requires exactly 1 answer, got 2`);
    })
});
