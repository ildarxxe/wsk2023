const { QuizManager } = require('./QuizManager');
const { Question } = require('./Question');
const { OutputManager } = require('./OutputManager');
const { InputReader } = require('./InputReader');

const mockOutputManager = new OutputManager();
mockOutputManager.display = jest.fn();

const mockInputReader = new InputReader();
mockInputReader.readAnswers = jest.fn();

const mockQuestions = [
    new Question(
        'What is 2 + 2?',
        ['3', '4', '5'],
        {
            correctAnswers: [2],
            checkAnswer: (answers) => answers.includes(2),
            maxAnswers: 1,
        }
    ),
    new Question(
        'What are the first two letters of the alphabet?',
        ['C', 'A', 'B'],
        {
            correctAnswers: [2, 3],
            checkAnswer: (answers) => {
                const sortedAnswers = [...answers].sort();
                return sortedAnswers.length === 2 && sortedAnswers[0] === 2 && sortedAnswers[1] === 3;
            },
            maxAnswers: 2,
        }
    )
];

describe('QuizManager', () => {
    let quizManager;

    beforeEach(() => {
        jest.clearAllMocks();
        quizManager = new QuizManager(mockQuestions, mockOutputManager, mockInputReader);
    });

    it('should display the first question and its choices', () => {
        quizManager.displayQuestion();

        expect(mockOutputManager.display).toHaveBeenCalledWith("Question 1: What is 2 + 2?");
        expect(mockOutputManager.display).toHaveBeenCalledWith("1. 3");
        expect(mockOutputManager.display).toHaveBeenCalledWith("2. 4");
        expect(mockOutputManager.display).toHaveBeenCalledWith("3. 5");

        expect(mockOutputManager.display).toHaveBeenCalledTimes(4);
    });

    it('should display "Correct!" for a correct answer and finish the quiz with a single question', async () => {
        mockInputReader.readAnswers.mockResolvedValueOnce([2]);

        const singleQuestionManager = new QuizManager([mockQuestions[0]], mockOutputManager, mockInputReader);

        await singleQuestionManager.startQuiz();

        expect(mockOutputManager.display).toHaveBeenCalledWith("Correct!\n", "green");
        expect(mockOutputManager.display).toHaveBeenCalledWith("Quiz finished!");

        expect(mockInputReader.readAnswers).toHaveBeenCalledTimes(1);
    });

    it('should correctly progress through multiple questions', async () => {
        mockInputReader.readAnswers.mockResolvedValueOnce([2]);
        mockInputReader.readAnswers.mockResolvedValueOnce([2, 3]);

        await quizManager.startQuiz();

        expect(mockOutputManager.display).toHaveBeenCalledWith("Question 1: What is 2 + 2?");
        expect(mockOutputManager.display).toHaveBeenCalledWith("Correct!\n", "green");

        expect(mockOutputManager.display).toHaveBeenCalledWith("Question 2: What are the first two letters of the alphabet?");
        expect(mockOutputManager.display).toHaveBeenCalledWith("Correct!\n", "green");

        expect(mockOutputManager.display).toHaveBeenCalledWith("Quiz finished!");

        expect(mockInputReader.readAnswers).toHaveBeenCalledTimes(2);
    });
});