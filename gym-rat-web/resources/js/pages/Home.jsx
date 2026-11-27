const features = [
    ['Registra tus series', 'Guarda peso, repeticiones y RPE en cada entrenamiento.'],
    ['Organiza tu rutina', 'Prepara tus planes y ajústalos conforme cambie tu entrenamiento.'],
    ['Consulta tu progreso', 'Vuelve a tus sesiones anteriores y compara tus cargas con el tiempo.'],
];

export default function Home() {
    return (
        <div className="min-h-screen bg-[#161614] text-[#f9f2e7]">
            <header className="border-b border-white/15">
                <div className="mx-auto flex max-w-6xl items-center justify-between gap-4 px-6 py-5">
                    <a href="/" className="text-2xl font-black tracking-tight uppercase">Gym Rat<span className="text-[#ff8951]">.</span></a>
                    <nav aria-label="Principal">
                        <a href="#funciones" className="text-sm font-semibold text-stone-300 hover:text-white">Cómo funciona</a>
                    </nav>
                </div>
            </header>

            <main>
                <section className="mx-auto grid max-w-6xl items-center gap-10 px-6 py-16 lg:grid-cols-[1.2fr_1fr] lg:gap-16 lg:py-24">
                    <div>
                        <h1 className="max-w-2xl text-5xl leading-[0.98] font-black tracking-tighter sm:text-7xl">
                            Tu diario de <span className="block text-[#ff8951]">entrenamiento.</span>
                        </h1>
                        <p className="mt-6 max-w-md text-lg leading-relaxed text-stone-300">
                            Registra tus series, organiza tu rutina y consulta cómo han cambiado tus cargas con el tiempo.
                        </p>
                        <a href="#funciones" className="mt-8 inline-flex rounded-md bg-[#ff8951] px-5 py-3 font-bold text-[#161614] hover:bg-[#ffa57e]">
                            Explorar funciones
                        </a>
                    </div>

                    <figure className="rounded-2xl border border-[#eadcc6] bg-[#f9f2e7] p-6 text-[#24211e] shadow-2xl sm:p-8">
                        <figcaption className="text-sm font-semibold text-[#6d5945]">Ejemplo de registro</figcaption>
                        <h2 className="mt-2 text-3xl font-black tracking-tight">Press de banca</h2>
                        <table className="mt-6 w-full text-left text-sm tabular-nums">
                            <thead className="border-b border-[#d6c9b6] text-[#6d5945]">
                                <tr>
                                    <th scope="col" className="pb-3">Serie</th>
                                    <th scope="col" className="pb-3">Peso</th>
                                    <th scope="col" className="pb-3">Reps</th>
                                    <th scope="col" className="pb-3">RPE</th>
                                </tr>
                            </thead>
                            <tbody className="divide-y divide-[#d6c9b6] font-semibold">
                                <tr><th scope="row" className="py-3">1</th><td>60 kg</td><td>8</td><td>7</td></tr>
                                <tr><th scope="row" className="py-3">2</th><td>60 kg</td><td>8</td><td>8</td></tr>
                                <tr><th scope="row" className="py-3">3</th><td>60 kg</td><td>7</td><td>8</td></tr>
                            </tbody>
                        </table>
                    </figure>
                </section>

                <section id="funciones" aria-labelledby="funciones-titulo" className="scroll-mt-6 bg-[#f9f2e7] text-[#24211e]">
                    <div className="mx-auto max-w-6xl px-6 py-14">
                        <h2 id="funciones-titulo" className="text-3xl font-black tracking-tight sm:text-4xl">Cómo funciona</h2>
                        <div className="mt-8 grid gap-6 md:grid-cols-3">
                            {features.map(([title, description]) => (
                                <article key={title} className="border-t-2 border-[#b64318] pt-4">
                                    <h3 className="text-xl font-bold">{title}</h3>
                                    <p className="mt-2 leading-relaxed text-[#604f41]">{description}</p>
                                </article>
                            ))}
                        </div>
                    </div>
                </section>
            </main>
        </div>
    );
}
