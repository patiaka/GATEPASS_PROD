// Composants <el-dialog> (modales de suppression, etc.)
import "@tailwindplus/elements";

// Les icônes sont désormais des SVG inline dans les vues : plus besoin de
// ré-initialiser une librairie d'icônes après chaque rendu Livewire.

// ── Chart.js (auto-hébergé) ────────────────────────────────────────────────
// Composant Alpine générique. Utilisation dans le blade :
//   <div wire:key="..." wire:ignore x-data="chartjs(@js($config))" style="height:240px">
//       <canvas x-ref="canvas"></canvas>
//   </div>
// Le wire:key (incluant les filtres) force le remplacement de l'élément quand
// les données changent : Alpine détruit l'ancien graphique (destroy) et en
// recrée un neuf (init) — robuste avec Livewire, aucune réutilisation de canvas.
import Chart from "chart.js/auto";

document.addEventListener("alpine:init", () => {
    window.Alpine.data("chartjs", (config) => ({
        chart: null,
        init() {
            const canvas = this.$refs.canvas;
            const existing = Chart.getChart(canvas);
            if (existing) existing.destroy();
            this.chart = new Chart(canvas, config);
        },
        destroy() {
            if (this.chart) {
                this.chart.destroy();
                this.chart = null;
            }
        },
    }));
});
