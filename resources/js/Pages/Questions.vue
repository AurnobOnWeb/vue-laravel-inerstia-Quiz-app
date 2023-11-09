<script setup>
import { router } from "@inertiajs/vue3";
import Layout from "../shared/Layout.vue";
import NewQuestions from "../shared/NewQuestions.vue";
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
const page = usePage();
const success = computed(() => page.props.flash.success);
const selectedAnswer = ref(null);
let answerId = 1;
const showNewQuestion = ref(false);
const showViewQuestionsModel = ref(false);
const selecteQuestion = ref(null);
const answers = ref([]);
const createQuestion = ref(null);
const newAnswers = ref([]);

const addNewAnswer = () => {
    const newAnswer = {
        id: answerId++,
        answer: "",
        correct_answer: 0,
    };
    newAnswers.value.push(newAnswer);
};
const handeRadioToggole = (Id) => {
    selectedAnswer.value = Id;
    newAnswers.value.forEach((answer) => {
        if (answer.id === Id) {
            answer.correct_answer = 1;
        } else {
            answer.correct_answer = 0;
        }
    });
};
const validateAnswer = () => {
    for (const answer of newAnswers.value) {
        if (answer.answer.trim() === "") {
            return false;
        }
    }
    return true;
};
const answerCount = () => {
    if (newAnswers.length < 4) {
        alert("four Answer required to submit");
    } else if (newAnswers.length === 4) {
        return true;
    }
    return false;
};
const submitQuestion = () => {
    if (!createQuestion.value) {
        alert("Question cannot be empyt");
        return false;
    }
    if (!validateAnswer() && !answerCount()) {
        alert("fill all input before submit");
        return false;
    }

    router.post("/questions", {
        question: createQuestion.value,
        answers: newAnswers.value,
    });
    router.on("success", () => {
        createQuestion.value = null;
        newAnswers.value = [];
    });
};

const props = defineProps({
    questions: Object,
    errors: Object,
});
const handleView = (index) => {
    showViewQuestionsModel.value = true;
    selecteQuestion.value = props.questions[index];
    answers.value = props.questions[index].answer;
};

// handle radio change and submit edited answers
const selectedEditAnswer = ref(null);
const handeRadioChange = (Id) => {
    selectedEditAnswer.value = Id;
    answers.value.forEach((answer) => {
        if (answer.id === Id) {
            answer.correct_answer = 1;
        } else {
            answer.correct_answer = 0;
        }
    });
};

const updateAnswers = () => {
    router.put("/answers", answers.value);
};

// Delete questions

const deleteQuestion = (id) => {
    router.on("before", () => {
        return confirm("are You sure");
    });
    router.delete("/question/" + id);
};
</script>
<template>
    <Layout>
        <button
            n
            class="btn btn-success"
            id="show-modal"
            @click="showNewQuestion = true"
        >
            Show Modal
        </button>

        <table class="table table-striped mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Questions</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(questions, index) in questions">
                    <th scope="row">{{ index + 1 }}</th>
                    <td>{{ questions.question }}</td>
                    <td>
                        <button
                            @click="handleView(index)"
                            class="btn btn-primary"
                        >
                            View
                        </button>
                        <button class="btn btn-success">Edit</button>
                        <button
                            @click="deleteQuestion(questions.id)"
                            class="btn btn-danger"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <Teleport to="body">
            <NewQuestions :show="showNewQuestion">
                <template #header>
                    <h5 class="modal-title" id="exampleModalLongTitle">
                        Add Questions
                    </h5>
                </template>
                <template #success>
                    <div
                        v-if="success"
                        class="alert alert-success"
                        role="alert"
                    >
                        <strong>{{ success }}</strong>
                    </div>
                </template>
                <template #body>
                    <form>
                        <div class="form-group">
                            <label for="newQuestion">New Question</label>
                            <input
                                type="text"
                                v-model="createQuestion"
                                class="form-control"
                                id="newQuestion"
                                aria-describedby="emailHelp"
                                placeholder="Add Question"
                            />
                        </div>
                        <table class="table table-striped mt-4">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Answers</th>
                                    <th scope="col">Correct?</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(answer, index) in newAnswers">
                                    <th scope="row">{{ answer.id }}</th>
                                    <td>
                                        <input
                                            v-model="answer.answer"
                                            type="text"
                                            class="form-control"
                                            aria-describedby="answers"
                                            placeholder="Add answer"
                                        />
                                    </td>
                                    <td>
                                        <input
                                            :checked="
                                                answer.correct_answer === 1
                                            "
                                            class="form-check-input"
                                            :value="answer.id"
                                            @change="
                                                handeRadioToggole(answer.id)
                                            "
                                            type="radio"
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </template>
                <template #footer>
                    <button
                        v-if="newAnswers.length < 4"
                        @click="addNewAnswer"
                        class="btn btn-primary"
                    >
                        +
                    </button>
                    <button
                        class="btn btn-danger"
                        @click="showNewQuestion = false"
                    >
                        Close
                    </button>
                    <button
                        @click="submitQuestion"
                        v-if="newAnswers.length > 3"
                        class="btn btn-success"
                    >
                        submit
                    </button>
                </template>
            </NewQuestions>

            <NewQuestions :show="showViewQuestionsModel">
                <template #header>View And Edit Question</template>
                <template #success>
                    <div
                        v-if="success"
                        class="alert alert-success"
                        role="alert"
                    >
                        <strong>{{ success }}</strong>
                    </div>
                </template>
                <template #body>
                    <p>
                        <strong>Q.{{ selecteQuestion.question }}</strong>
                    </p>
                    <table class="table table-striped mt-4">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Answers</th>
                                <th scope="col">Correct?</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(answer, index) in answers">
                                <th scope="row">{{ index + 1 }}</th>
                                <td>
                                    <input
                                        v-model="answer.answer"
                                        type="text"
                                        class="form-control"
                                        aria-describedby="answers"
                                        placeholder="Add answer"
                                    />
                                </td>
                                <td>
                                    <input
                                        :checked="answer.correct_answer === 1"
                                        class="form-check-input"
                                        :value="answer.id"
                                        @change="handeRadioChange(answer.id)"
                                        type="radio"
                                    />
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </template>

                <template #footer>
                    <button
                        class="btn btn-danger"
                        @click="showViewQuestionsModel = false"
                    >
                        Close
                    </button>
                    <button class="btn btn-success" @click="updateAnswers()">
                        Submit
                    </button>
                </template>
            </NewQuestions>
        </Teleport>
    </Layout>
</template>

<style lang="scss" scoped></style>
