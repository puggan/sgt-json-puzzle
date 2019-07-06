all: dominosa fifteen filling galaxies keen lightup loopy magnets map pattern signpost singles slant solo tents towers unequal unruly
bin/.keep:
	mkdir bin
	touch bin/.keep

clean:
	rm bin/*solver

BASE_DEP=c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c
GCC_FLAGS=-D STANDALONE_SOLVER -Wall

dominosa: bin/dominosasolver
	bin/dominosasolver || echo
bin/dominosasolver: bin/.keep $(BASE_DEP) c/dsf.c c/findloop.c c/laydomino.c c/sort.c c/dominosa.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/findloop.c c/laydomino.c c/sort.c c/dominosa.c -o bin/dominosasolver

fifteen: bin/fifteensolver
	bin/fifteensolver || echo
bin/fifteensolver: bin/.keep $(BASE_DEP) c/fifteen.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/fifteen.c -o bin/fifteensolver

filling: bin/fillingsolver
	bin/fillingsolver
bin/fillingsolver: bin/.keep $(BASE_DEP) c/dsf.c c/filling.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/filling.c -o bin/fillingsolver

galaxies: bin/galaxiessolver
	bin/galaxiessolver 6x5:khfzanevg
bin/galaxiessolver: bin/.keep $(BASE_DEP) c/dsf.c c/galaxies.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/galaxies.c -lm -o bin/galaxiessolver

keen: bin/keensolver
	bin/keensolver || echo
bin/keensolver: bin/.keep $(BASE_DEP) c/dsf.c c/latin.c c/latin.h c/matching.c c/matching.h c/tree234.c c/tree234.h c/keen.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/latin.c c/matching.c c/tree234.c c/keen.c -o bin/keensolver

lightup: bin/lightupsolver
	bin/lightupsolver || echo
bin/lightupsolver: bin/.keep $(BASE_DEP) c/combi.c c/lightup.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/combi.c c/lightup.c -o bin/lightupsolver

loopy: bin/loopysolver
	bin/loopysolver || echo
bin/loopysolver: bin/.keep $(BASE_DEP) c/dsf.c c/grid.c c/grid.h c/loopgen.c c/loopgen.h c/penrose.c c/penrose.h c/tree234.c c/tree234.h c/loopy.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/grid.c c/loopgen.c c/penrose.c c/tree234.c c/loopy.c -lm -o bin/loopysolver

magnets: bin/magnetssolver
	bin/magnetssolver
bin/magnetssolver: bin/.keep $(BASE_DEP) c/laydomino.c c/magnets.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/laydomino.c c/magnets.c -o bin/magnetssolver

map: bin/mapsolver
	bin/mapsolver || echo
bin/mapsolver: bin/.keep $(BASE_DEP) c/dsf.c c/map.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/map.c -lm -o bin/mapsolver

pattern: bin/patternsolver
	bin/patternsolver
bin/patternsolver: bin/.keep $(BASE_DEP) c/pattern.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/pattern.c -o bin/patternsolver

signpost: bin/signpostsolver
	bin/signpostsolver -v
bin/signpostsolver: bin/.keep $(BASE_DEP) c/dsf.c c/signpost.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/signpost.c -lm -o bin/signpostsolver

singles: bin/singlessolver
	bin/singlessolver || echo
bin/singlessolver: bin/.keep $(BASE_DEP) c/dsf.c c/latin.c c/latin.h c/matching.c c/matching.h c/tree234.c c/tree234.h c/singles.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/latin.c c/matching.c c/tree234.c c/singles.c -o bin/singlessolver

slant: bin/slantsolver
	bin/slantsolver || echo
bin/slantsolver: bin/.keep $(BASE_DEP) c/dsf.c c/findloop.c c/slant.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/findloop.c c/slant.c -o bin/slantsolver

solo: bin/solosolver
	bin/solosolver
bin/solosolver: bin/.keep $(BASE_DEP) c/divvy.c c/dsf.c c/solo.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/divvy.c c/dsf.c c/solo.c -o bin/solosolver

tents: bin/tentssolver
	bin/tentssolver || echo
bin/tentssolver: bin/.keep $(BASE_DEP) c/dsf.c c/matching.c c/matching.h c/tents.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/dsf.c c/matching.c c/tents.c -o bin/tentssolver

towers: bin/towerssolver
	bin/towerssolver || echo
bin/towerssolver: bin/.keep $(BASE_DEP) c/latin.c c/latin.h c/matching.c c/matching.h c/tree234.c c/tree234.h c/towers.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/latin.c c/matching.c c/tree234.c c/towers.c -o bin/towerssolver

unequal: bin/unequalsolver
	bin/unequalsolver 4:2,0,0,0,0D,0R,0,0,0D,0,0,0L,0,0,0L,2
bin/unequalsolver: bin/.keep $(BASE_DEP) c/latin.c c/latin.h c/matching.c c/matching.h c/tree234.c c/tree234.h c/unequal.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/latin.c c/matching.c c/tree234.c c/unequal.c -o bin/unequalsolver

unruly: bin/unrulysolver
	bin/unrulysolver || echo
bin/unrulysolver: bin/.keep $(BASE_DEP) c/unruly.c
	gcc $(GCC_FLAGS) $(BASE_DEP) c/unruly.c -o bin/unrulysolver
