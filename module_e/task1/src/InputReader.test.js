const { InputReader } = require("./InputReader");
const {Question} = require("./Question");
const readline = require("node:readline");

const mockInterface = {
    question: jest.fn(),
    close: jest.fn(),
}

jest.mock("readline", () => ({
    createInterface: jest.fn(() => mockInterface),
}))

const mockQuestion = {
    choices: [1, 2, 3, 4],
    strategy: {
        maxAnswers: 1,
    },
};

describe("InputReader", () => {
    let inputReader;

    beforeEach(() => {
        inputReader = new InputReader();
        jest.clearAllMocks()
    })

    describe("close()", () => {
        it("should close the readline interface", () => {
            inputReader.close()
            expect(mockInterface.close).toHaveBeenCalledTimes(1);
        })
    })

    describe("readAnswers()", () => {
        it("should return a promise that resolves with a single valid number", async () => {
            mockInterface.question.mockImplementationOnce((query, callback) => {
                callback("1")
            })

            const resultPromise = inputReader.readAnswers(mockQuestion)

            expect(mockInterface.question).toHaveBeenCalledWith(
                "Enter your answer: ",
                expect.any(Function)
            );

            const result = await resultPromise
            expect(result).toEqual([1])
        })

        it("should return a promise that resolves with multiple valid numbers when maxAnswers > 1", async () => {
            const mockQuestionMultiple = {
                ...mockQuestion,
                strategy: { maxAnswers: 2 },
            };

            mockInterface.question.mockImplementationOnce((query, callback) => {
                callback("1,3");
            });

            const resultPromise = inputReader.readAnswers(mockQuestionMultiple);

            expect(mockInterface.question).toHaveBeenCalledWith(
                "Enter your answers (separated by a comma): ",
                expect.any(Function)
            );

            const result = await resultPromise;
            expect(result).toEqual([1, 3]);
        });

        it("should handle multiple invalid answers and eventually resolve with a valid answer", async () => {
            mockInterface.question.mockImplementationOnce((query, callback) => {
                callback("a,5");
            });
            mockInterface.question.mockImplementationOnce((query, callback) => {
                callback("6");
            });
            mockInterface.question.mockImplementationOnce((query, callback) => {
                callback("2");
            });

            const resultPromise = inputReader.readAnswers(mockQuestion);

            expect(mockInterface.question).toHaveBeenCalledWith(
                "Enter your answer: ",
                expect.any(Function)
            );

            expect(mockInterface.question).toHaveBeenCalledWith(
                "Invalid choices: a, 5\nEnter your answer: ",
                expect.any(Function)
            );

            expect(mockInterface.question).toHaveBeenCalledWith(
                "Invalid choice: 6\nEnter your answer: ",
                expect.any(Function)
            );

            const result = await resultPromise;
            expect(result).toEqual([2]);
            expect(mockInterface.question).toHaveBeenCalledTimes(3);
        });
    })

    describe("getInvalidAnswers()", () => {
        it("should return an array of invalid choices", () => {
            const invalidAnswers = inputReader.getInvalidAnswers(
                "a, 5, 0, 4, 10",
                mockQuestion
            );
            expect(invalidAnswers).toEqual(["a", "5", "0", "10"]);
        });

        it("should return an empty array for a valid single choice", () => {
            const invalidAnswers = inputReader.getInvalidAnswers("3", mockQuestion);
            expect(invalidAnswers).toEqual([]);
        });

        it("should return an empty array for multiple valid choices", () => {
            const mockQuestionMultiple = {
                ...mockQuestion,
                strategy: { maxAnswers: 2 },
            };
            const invalidAnswers = inputReader.getInvalidAnswers(
                "1, 4",
                mockQuestionMultiple
            );
            expect(invalidAnswers).toEqual([]);
        });

        it("should handle invalid answers with extra spaces", () => {
            const invalidAnswers = inputReader.getInvalidAnswers(
                " 1 , 5,  -1 ",
                mockQuestion
            );
            expect(invalidAnswers).toEqual(["5", "-1"]);
        });
    });
});
