import "@tailwindplus/elements";
import { createIcons, Plus, Pencil, Trash2, Eye, Search, X } from "lucide";

const refreshIcons = () =>
    createIcons({ icons: { Plus, Pencil, Trash2, Eye, Search, X } });

document.addEventListener("DOMContentLoaded", refreshIcons);
document.addEventListener("livewire:navigated", refreshIcons);
