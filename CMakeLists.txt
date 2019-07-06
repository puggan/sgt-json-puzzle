cmake_minimum_required(VERSION 3.14)
project(sgt_json_puzzle C)

set(CMAKE_C_STANDARD 11)

include_directories(c)

add_compile_definitions(STANDALONE_SOLVER)

add_executable(solosolver
    c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/dsf.c c/divvy.c
    c/solo.c)

add_executable(unrulysolver
    c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
    c/unruly.c)
