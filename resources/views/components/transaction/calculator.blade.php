<div x-data="calculatorApp()" x-init="watchKeyboard()" x-show="calculator"
    x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-10"
    x-transition:enter-end="opacity-100 translate-x-0" x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-10"
    class="lg:w-1/4 w-full mt-6 lg:mt-0 lg:ml-auto mb-5 h-[450px] border p-4 rounded-md shadow-md">
    <h2 class="text-black text-lg font-semibold mb-4">Calculator</h2>

    <!-- Input (editable) -->
    <div class="flex">
        <input type="text" x-model="inputCalcu"
            class="border border-gray-300 h-16 rounded-md p-2 w-full text-right text-xl" />
    </div>

    <!-- Buttons -->
    <div class="flex flex-col justify-between mt-4 space-y-2">
        <div class="grid grid-cols-4 gap-2">
            <button @click="clearInput()" class="bg-gray-800 text-white rounded-md shadow-sm font-bold py-3">C</button>
            <button @click="append('÷')"
                class="bg-gray-800 text-white rounded-md shadow-sm py-3 flex justify-center"><x-heroicon-s-divide
                    class="w-6 h-6" /></button>
            <button @click="append('×')"
                class="bg-gray-800 text-white rounded-md shadow-sm py-3 flex justify-center"><x-heroicon-s-x-mark
                    class="w-6 h-6" /></button>
            <button @click="backspace()"
                class="bg-gray-800 text-white rounded-md shadow-sm py-3 flex justify-center"><x-heroicon-o-backspace
                    class="w-6 h-6" /></button>

            <template x-for="n in [7, 8, 9]">
                <button @click="append(n)" class="bg-gray-300 text-xl text-black rounded-md shadow-sm font-bold py-3"
                    x-text="n"></button>
            </template>
            <button @click="append('-')"
                class="bg-gray-800 text-white rounded-md shadow-sm py-3 flex justify-center"><x-heroicon-o-minus
                    class="w-6 h-6" /></button>

            <template x-for="n in [4, 5, 6]">
                <button @click="append(n)" class="bg-gray-300 text-xl text-black rounded-md shadow-sm font-bold py-3"
                    x-text="n"></button>
            </template>
            <button @click="append('+')"
                class="bg-gray-800 text-white rounded-md shadow-sm py-3 flex justify-center"><x-heroicon-o-plus
                    class="w-6 h-6" /></button>

            <template x-for="n in [1, 2, 3]">
                <button @click="append(n)" class="bg-gray-300 text-xl text-black rounded-md shadow-sm font-bold py-3"
                    x-text="n"></button>
            </template>
            <button @click="calculate()"
                class="bg-emerald-500 text-white rounded-md shadow-sm text-4xl font-bold py-3 row-span-2 flex items-center justify-center">=</button>

            <button disabled class="bg-gray-200 rounded-md"></button>
            <button @click="append(0)"
                class="bg-gray-300 text-xl text-black rounded-md shadow-sm font-bold py-3">0</button>
            <button @click="append('.')"
                class="bg-gray-300 text-xl text-black rounded-md shadow-sm font-bold py-3">.</button>
        </div>
    </div>
</div>


<script>
    function calculatorApp() {
        return {
            inputCalcu: '',
            calculated: false,
            append(char) {
                if (this.calculated && /[0-9.]/.test(char)) {
                    this.inputCalcu = '';
                }
                if (['÷', '×', '+', '-'].includes(char)) {
                    if (!['÷', '×', '+', '-'].includes(this.inputCalcu.slice(-1))) {
                        this.inputCalcu += char;
                    }
                } else {
                    this.inputCalcu += char;
                }

                this.calculated = false;
            },
            clearInput() {
                this.inputCalcu = '';
                this.calculated = false;
            },
            backspace() {
                this.inputCalcu = this.inputCalcu.slice(0, -1);
            },
            calculate() {
                try {
                    let clean = this.inputCalcu.replace(/×/g, '*').replace(/÷/g, '/');
                    if (['/', '*', '+', '-'].includes(clean.slice(-1))) {
                        clean = clean.slice(0, -1);
                    }
                    this.inputCalcu = eval(clean).toString();
                    this.calculated = true;
                } catch {
                    this.inputCalcu = 'Error';
                    this.calculated = true;
                }
            },
            watchKeyboard() {
                window.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        this.calculate();
                    }
                });
            }
        };
    }
</script>
