const { MultipleChoiceStrategy } = require("./MultipleChoiceStrategy");

const correctAnswers = [2, 3];
const mcs = new MultipleChoiceStrategy(correctAnswers);

describe("MultipleChoiceStrategy", () => {
    it("should return true for a correct set of answers", () => {
        const userAnswers = [2, 3];
        const result = mcs.checkAnswer(userAnswers);

        expect(result).toBe(true);
    });

    it("should return false if the number of answers is incorrect", () => {
        const userAnswers = [2];
        const result = mcs.checkAnswer(userAnswers);
        expect(result).toBe(false);
    });

    it("should return false if the answers are incorrect", () => {
        const userAnswers = [2, 4];
        const result = mcs.checkAnswer(userAnswers);
        expect(result).toBe(false);
    });

    it("should return false if the correct answers are in a different order", () => {
        const userAnswers = [3, 2];
        const result = mcs.checkAnswer(userAnswers);
        expect(result).toBe(true);
    });
});