<script setup lang="ts">
import MarketingLayout from '@/layouts/MarketingLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { login } from '@/routes';
</script>

<template>
    <Head title="Register — Stack Notes" />

    <MarketingLayout>
        <section class="relative overflow-hidden border-b border-slate-200/60 bg-white/60">
            <div class="pointer-events-none absolute inset-0" aria-hidden="true">
                <div
                    class="absolute -top-24 right-0 h-80 w-80 rounded-full bg-violet-200/35 blur-3xl"
                />
                <div
                    class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-teal-200/25 blur-3xl"
                />
            </div>

            <div
                class="relative mx-auto max-w-6xl px-4 py-14 sm:px-6 sm:py-16 lg:px-8"
            >
                <p
                    class="mb-3 inline-flex rounded-full bg-violet-100/90 px-3 py-1 text-xs font-semibold tracking-wider text-violet-900 uppercase ring-1 ring-violet-300/60"
                >
                    Account
                </p>
                <h1
                    class="max-w-2xl text-4xl font-bold tracking-tight text-slate-800 sm:text-5xl"
                >
                    Create an account
                </h1>
                <p class="mt-4 max-w-2xl text-lg text-slate-600">
                    Join Stack Notes to manage your subscription and
                    preferences in one place.
                </p>
            </div>
        </section>

        <section class="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-5 lg:gap-16">
                <div class="lg:col-span-3">
                    <form
                        class="space-y-6 rounded-2xl border border-slate-200/80 bg-white p-6 shadow-sm sm:p-8"
                        action="#"
                        @submit.prevent
                    >
                        <div>
                            <label
                                for="register-name"
                                class="block text-sm font-medium text-slate-800"
                                >Name</label
                            >
                            <input
                                id="register-name"
                                type="text"
                                name="name"
                                autocomplete="name"
                                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none"
                                placeholder="Ada Lovelace"
                            />
                        </div>
                        <div>
                            <label
                                for="register-email"
                                class="block text-sm font-medium text-slate-800"
                                >Email</label
                            >
                            <input
                                id="register-email"
                                type="email"
                                name="email"
                                autocomplete="email"
                                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none"
                                placeholder="you@example.com"
                            />
                        </div>
                        <div>
                            <label
                                for="register-password"
                                class="block text-sm font-medium text-slate-800"
                                >Password</label
                            >
                            <input
                                id="register-password"
                                type="password"
                                name="password"
                                autocomplete="new-password"
                                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none"
                                placeholder="••••••••"
                            />
                        </div>
                        <div>
                            <label
                                for="register-password-confirm"
                                class="block text-sm font-medium text-slate-800"
                                >Confirm password</label
                            >
                            <input
                                id="register-password-confirm"
                                type="password"
                                name="password_confirmation"
                                autocomplete="new-password"
                                class="mt-2 w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 placeholder:text-slate-400 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 focus:outline-none"
                                placeholder="••••••••"
                            />
                        </div>
                        <button
                            type="submit"
                            class="w-full rounded-xl bg-linear-to-r from-teal-600 to-teal-700 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-teal-600/20 transition hover:from-teal-500 hover:to-teal-600 sm:w-auto"
                        >
                            Create account
                        </button>
                    </form>
                </div>

                <aside class="lg:col-span-2">
                    <div
                        class="rounded-2xl border border-violet-200/80 bg-linear-to-br from-violet-50/90 to-white p-6"
                    >
                        <h2
                            class="font-heading text-lg font-bold text-slate-800"
                        >
                            Already have an account?
                        </h2>
                        <p class="mt-3 text-sm text-slate-600">
                            Sign in to your existing Stack Notes account to
                            manage your subscription and preferences.
                        </p>
                        <Link
                            :href="login.url()"
                            class="mt-4 inline-flex rounded-xl border border-violet-200 bg-white px-5 py-2.5 text-sm font-semibold text-violet-800 shadow-sm transition hover:border-violet-300 hover:bg-violet-50"
                        >
                            Sign in
                        </Link>

                        <div class="mt-6 border-t border-violet-200/60 pt-6">
                            <h2
                                class="font-heading text-lg font-bold text-slate-800"
                            >
                                Demo notice
                            </h2>
                            <p class="mt-2 text-sm text-slate-600">
                                This is a demo register form. Wire it to your
                                authentication backend when you are ready to
                                ship.
                            </p>
                        </div>
                    </div>
                </aside>
            </div>
        </section>
    </MarketingLayout>
</template>
