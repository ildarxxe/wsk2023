const { InputReader } = require("./InputReader");

const mockQuestion = jest.fn();
const mockClose = jest.fn();

jest.mock("readline", () => ({
    createInterface: jest.fn(() => ({
        question: mockQuestion,
        close: mockClose,
    }))
}))

describe("InputReader", () => {
    let reader;

    beforeEach(() => {
        mockQuestion.mockClear();
        mockClose.mockClear();

        reader = new InputReader();
    })

    it("should be reading one answer and return array [1]", async () => {
        const mockQuestionObjectSingle = {
            text: "Text",
            choices: ["Choice 1", "Choice 2"],
            strategy: { maxAnswers: 1 }
        };

        mockQuestion.mockImplementationOnce((query, callback) => {
            expect(query).toBe("Enter your answer: ");
            callback("1")
        });

        const result = await reader.readAnswers(mockQuestionObjectSingle);

        expect(result).toEqual([1]);

        expect(mockQuestion).toHaveBeenCalledTimes(1);
        expect(mockClose).not.toHaveBeenCalled()
    })
})