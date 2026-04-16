<script setup>
import { ref, watch } from 'vue';
import { router, useForm } from '@inertiajs/vue3';
import draggable from 'vuedraggable';

const props = defineProps({
    tasks: Array,
    projects: Array,
    selectedProjectId: Number
});

const projectForm = useForm({
    name: ''
});

const localTasks = ref([...props.tasks]);

const isEditingProject = ref(false);


watch(() => props.tasks, (newTasks) => {
    localTasks.value = [...newTasks];
}, { deep: true });

// For adding new tasks
const form = useForm({
    name: '',
    project_id: props.selectedProjectId
});

watch(() => props.selectedProjectId, (newId) => {
    form.project_id = newId;
});

// Reorder logic
const handleReorder = () => {
    const ids = localTasks.value.map(t => t.id);
    router.patch(route('tasks.reorder'), { tasks: ids }, {
        preserveScroll: true,
    });
};

// Filter logic
const changeProject = (id) => {
    router.get(route('tasks.index'), { project_id: id });
};

const addTask = () => {
    form.post(route('tasks.store'), {
        onSuccess: () => form.reset('name'),
    });
};

const editProjectForm = useForm({
    name: props.projects.find(p => p.id === props.selectedProjectId)?.name || ''
});

const updateProject = () => {
    editProjectForm.patch(route('projects.update', props.selectedProjectId), {
        onSuccess: () => isEditingProject.value = false
    });
};

const deleteProject = () => {
    if (confirm('Are you sure? This will delete all tasks in this project.')) {
        router.delete(route('projects.destroy', props.selectedProjectId));
    }
};

const addProject = () => {
    projectForm.post(route('projects.store'), {
        onSuccess: () => {
            projectForm.reset();
            // Optional: You could redirect the user to the new project here
        },
    });
};
</script>

<template>
    <div class="p-8 max-w-xl mx-auto">
        <h1 class="text-2xl font-bold mb-6">Task Manager</h1>

        <div class="mb-10 p-6 bg-gray-50 rounded-lg border border-gray-200">
            <h2 class="text-sm font-bold text-gray-700 uppercase tracking-wide mb-4">Create New Project</h2>
            <form @submit.prevent="addProject" class="flex gap-2">
                <input v-model="projectForm.name" type="text" placeholder="Project Name"
                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    :class="{ 'border-red-500': projectForm.errors.name }" />
                <button type="submit" :disabled="projectForm.processing"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 disabled:opacity-50 transition">
                    Create Project
                </button>
            </form>
            <div v-if="projectForm.errors.name" class="text-red-500 text-xs mt-1 italic">
                {{ projectForm.errors.name }}
            </div>
        </div>
        <div class="mb-8">
            <div v-if="!isEditingProject" class="flex items-center gap-2">
                <select @change="changeProject($event.target.value)" :value="selectedProjectId"
                    class="flex-1 rounded border-gray-300 shadow-sm">
                    <option value="" disabled selected>Select a project...</option>
                    <option v-for="p in projects" :key="p.id" :value="p.id">{{ p.name }}</option>
                </select>

                <button @click="isEditingProject = true"
                    class="text-blue-600 px-2 py-1 text-sm border border-blue-600 rounded">Edit</button>
                <button @click="deleteProject"
                    class="text-red-600 px-2 py-1 text-sm border border-red-600 rounded">Delete</button>
            </div>

            <form v-else @submit.prevent="updateProject" class="flex items-center gap-2">
                <input v-model="editProjectForm.name" type="text" class="flex-1 rounded border-gray-300" />
                <button type="submit" class="bg-blue-600 text-white px-3 py-1 rounded">Save</button>
                <button @click="isEditingProject = false" type="button" class="text-gray-500">Cancel</button>
            </form>
        </div>

        <form @submit.prevent="addTask" class="flex gap-2 mb-6">
            <input v-model="form.name" type="text" class="flex-1 rounded border-gray-300" placeholder="New task...">
            <button class="bg-blue-500 text-white px-4 py-2 rounded">Add</button>
        </form>

        <draggable v-model="localTasks" @end="handleReorder" item-key="id" class="space-y-2">
            <template #item="{ element }">
                <div class="flex items-center p-4 bg-white shadow rounded border border-gray-100">
                    <span class="drag-handle cursor-move mr-3 text-gray-400">☰</span>
                    <span class="flex-1">#{{ element.priority }} - {{ element.name }}</span>
                    <button @click="router.delete(route('tasks.destroy', element.id))"
                        class="text-red-400">Delete</button>
                </div>
            </template>
        </draggable>
    </div>
</template>