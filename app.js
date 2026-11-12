import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

Chart.defaults.font.family = "'Plus Jakarta Sans', system-ui, sans-serif";
Chart.defaults.color = '#64748b';

const palette = { ocean: '#087CA7', aqua: '#16C5D8', navy: '#071B33' };

Alpine.data('chart', (config) => ({
    instance: null,
    init() {
        const dataset = config.datasets ?? [];
        this.instance = new Chart(this.$el, {
            type: config.type ?? 'bar',
            data: {
                labels: config.labels,
                datasets: dataset.map((set, index) => ({
                    borderColor: index === 0 ? palette.ocean : palette.aqua,
                    backgroundColor: config.type === 'line' ? 'rgba(22, 197, 216, 0.15)' : (index === 0 ? palette.ocean : palette.aqua),
                    borderRadius: 8,
                    tension: 0.35,
                    fill: config.type === 'line',
                    pointBackgroundColor: palette.ocean,
                    ...set,
                })),
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: dataset.length > 1 } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#e2e8f0' }, border: { display: false }, title: { display: !!config.yTitle, text: config.yTitle } },
                    x: { grid: { display: false }, border: { display: false } },
                },
            },
        });
    },
    destroy() {
        this.instance?.destroy();
    },
}));

Alpine.data('quiz', (config) => ({
    questions: config.questions,
    index: config.startIndex,
    selected: null,
    feedback: null,
    loading: false,
    error: null,
    get current() {
        return this.questions[this.index];
    },
    get isLast() {
        return this.index === this.questions.length - 1;
    },
    get progress() {
        return Math.round(((this.index + (this.feedback ? 1 : 0)) / this.questions.length) * 100);
    },
    async submit() {
        if (!this.selected || this.loading) return;
        this.loading = true;
        this.error = null;
        try {
            const response = await fetch(config.answerUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ question_id: this.current.id, choice_id: this.selected }),
            });
            if (!response.ok) throw new Error('failed');
            this.feedback = await response.json();
        } catch (e) {
            this.error = 'Could not submit your answer. Please try again.';
        } finally {
            this.loading = false;
        }
    },
    next() {
        if (this.isLast) {
            this.$refs.finish.submit();
            return;
        }
        this.index += 1;
        this.selected = null;
        this.feedback = null;
    },
    choiceClass(choice) {
        if (!this.feedback) {
            return this.selected === choice.id ? 'border-ocean bg-mist' : 'border-slate-200 bg-white hover:border-ocean';
        }
        if (choice.id === this.feedback.correct_choice_id) return 'border-emerald-500 bg-emerald-50';
        if (choice.id === this.feedback.chosen_choice_id) return 'border-red-400 bg-red-50';
        return 'border-slate-200 bg-white opacity-60';
    },
}));

document.addEventListener('submit', (event) => {
    const message = event.target.dataset?.confirm;
    if (message && !window.confirm(message)) {
        event.preventDefault();
    }
});

Alpine.start();
