<script setup>
import { Input } from '@/components/ui/input';
import { ref, computed, defineProps, watch } from 'vue';

// --- PROPS ---
const props = defineProps({
  id: { type: String, required: false },
  name: { type: String, required: false },
  placeholder: { type: String, required: false },
  value: { type: [String, Number], required: false },
});

// --- STATE ---
const amount = ref(props.value ?? 0);
const isFocused = ref(false);
const display = ref(''); // separate display string for the input

// --- FORMATTER ---
const formatter = new Intl.NumberFormat('id-ID', {
  style: 'decimal',
  maximumFractionDigits: 0,
});

// --- WATCHERS ---
// Whenever amount changes (and not focused), reformat display
watch(amount, (val) => {
  if (!isFocused.value) {
    display.value = val ? formatter.format(val) : '';
  }
});

// --- HANDLERS ---
const handleFocus = () => {
  isFocused.value = true;
  // Show raw number for editing
  display.value = amount.value?.toString() ?? '';
};

const handleBlur = () => {
  isFocused.value = false;
  // Reformat with locale style
  display.value = amount.value ? formatter.format(amount.value) : '';
};

const handleInput = (event) => {
  const rawValue = event.target.value;
  const numericString = rawValue.replace(/\D/g, '');

  if (numericString === '') {
    amount.value = null;
    display.value = '';
  } else {
    amount.value = parseInt(numericString, 10);
    display.value = numericString; // let user see what they typed while editing
  }
};
</script>

<template>
  <Input
    type="text"
    :id="props.id"
    v-model="display"
    @focus="handleFocus"
    @blur="handleBlur"
    @input="handleInput"
    inputmode="numeric"
    :placeholder="props.placeholder"
  />
  
  <input type="text" class="tw-hidden" :name="props.name" v-model="amount" >
</template>
