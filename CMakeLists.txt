cmake_minimum_required(VERSION 3.13)
project(sgt_json_puzzle C)

set(CMAKE_C_STANDARD 11)

include_directories(c)

# Only make Standalone solvers
add_compile_definitions(STANDALONE_SOLVER)

# Include Math-library when needed
link_libraries(m)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/findloop.c c/laydomino.c c/sort.c c/dominosa.c -o bin/dominosasolver
add_executable(dominosasolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c c/findloop.c c/laydomino.c c/sort.c
        c/dominosa.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/fifteen.c -o bin/fifteensolver
add_executable(fifteensolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/fifteen.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/filling.c -o bin/fillingsolver
add_executable(fillingsolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c
        c/filling.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/galaxies.c -lm -o bin/galaxiessolver
add_executable(galaxiessolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c
        c/galaxies.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/latin.c c/matching.c c/tree234.c c/keen.c -o bin/keensolver
add_executable(keensolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c c/latin.c c/matching.c c/tree234.c
        c/keen.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/combi.c c/lightup.c -o bin/lightupsolver
add_executable(lightupsolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/combi.c
        c/lightup.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/grid.c c/loopgen.c c/penrose.c c/tree234.c c/loopy.c -lm -o bin/loopysolver
add_executable(loopysolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c c/grid.c c/loopgen.c c/penrose.c c/tree234.c
        c/loopy.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/laydomino.c c/magnets.c -o bin/magnetssolver
add_executable(magnetssolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/laydomino.c
        c/magnets.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/map.c -lm -o bin/mapsolver
add_executable(mapsolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c
        c/map.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/pattern.c -o bin/patternsolver
add_executable(patternsolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/pattern.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/signpost.c -lm -o bin/signpostsolver
add_executable(signpostsolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c
        c/signpost.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/latin.c c/matching.c c/tree234.c c/singles.c -o bin/singlessolver
add_executable(singlessolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c c/latin.c c/matching.c c/tree234.c
        c/singles.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/findloop.c c/slant.c -o bin/slantsolver
add_executable(slantsolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c c/findloop.c
        c/slant.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/divvy.c c/dsf.c c/solo.c -o bin/solosolver
add_executable(solosolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c c/divvy.c
        c/solo.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/dsf.c c/matching.c c/tents.c -o bin/tentssolver
add_executable(tentssolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/dsf.c c/matching.c
        c/tents.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/latin.c c/matching.c c/tree234.c c/towers.c -o bin/towerssolver
add_executable(towerssolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/latin.c c/matching.c c/tree234.c
        c/towers.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/latin.c c/matching.c c/tree234.c c/unequal.c -o bin/unequalsolver
add_executable(unequalsolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/latin.c c/matching.c c/tree234.c
        c/unequal.c)

# gcc -D STANDALONE_SOLVER $(BASE_DEP) c/unruly.c -o bin/unrulysolver
add_executable(unrulysolver
        c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
        c/unruly.c)
