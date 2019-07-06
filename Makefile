all: solo unruly
bin/.keep:
	mkdir bin
	touch bin/.keep

#dominosa.c
#fifteen.c
#filling.c
#galaxies.c
#keen.c
#latin.c
#lightup.c
#loopy.c
#magnets.c
#map.c
#pattern.c
#pearl.c
#signpost.c
#singles.c
#slant.c

#solo.c
solo: bin/solosolver
bin/solosolver: bin/.keep c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/dsf.c c/divvy.c c/solo.c
	gcc -D STANDALONE_SOLVER c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/dsf.c c/divvy.c c/solo.c -o bin/solosolver

#tents.c
#towers.c
#unequal.c

#unruly.c
unruly: bin/unrulysolver
bin/unrulysolver: bin/.keep c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/unruly.c
	gcc -D STANDALONE_SOLVER c/puzzles.h c/nullfe.c c/malloc.c c/misc.c c/random.c c/unruly.c -o bin/unrulysolver
