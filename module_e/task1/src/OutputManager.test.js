const { OutputManager } = require("./OutputManager");

let mockLog;

beforeEach(() => {
    mockLog = jest.spyOn(console, "log").mockImplementation(() => {});
})

afterEach(() => {
    mockLog.mockRestore();
})

describe("OutputManager", () => {
    let outputManager;

    beforeEach(() => {
        outputManager = new OutputManager();
    })

    describe("display(message)", () => {
        it("should log the message without any color codes when color is not specified", () => {
            const message = "Default message"
            outputManager.display(message)
            expect(mockLog).toHaveBeenCalledTimes(1)
            expect(mockLog).toHaveBeenCalledWith(message)
        })
    })

    describe("display(message, 'red')", () => {
        it("should log the message with red color codes", () => {
            const message = "This is a red message.";
            const redColorCode = "\x1b[31m%s\x1b[0m";

            outputManager.display(message, "red")
            expect(mockLog).toHaveBeenCalledTimes(1)
            expect(mockLog).toHaveBeenCalledWith(redColorCode, message)
        })
    })

    describe("display(message, 'green')", () => {
        it("should log the message with green color codes", () => {
            const message = "This is a green message.";
            const greenColorCode = "\x1b[32m%s\x1b[0m";

            outputManager.display(message, "green");

            expect(mockLog).toHaveBeenCalledTimes(1);
            expect(mockLog).toHaveBeenCalledWith(greenColorCode, message);
        });
    });
});
