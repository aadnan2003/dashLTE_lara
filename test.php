<?php

interface MyInterface extends MySecondInterface, MyThirdInterface
{
    //abstract
    function fn1();
}

interface MySecondInterface
{
    function fn2();
}

interface MyThirdInterface
{
}


// abstract class MyFirstClass implements MyInterface
// {
// }

class MyFirstClass implements MyInterface
{
    function fn1()
    {
    }

    function fn2()
    {
    }
}

// abstract class MyAbstractClass
// {
//     function normalFunction()
//     {
//     }

//     abstract function abstractFunction();
// }

// class MyClass extends MyAbstractClass
// {
//     function abstractFunction()
//     {
//     }
// }

// class MyClass implements MyAbstractClass
// {
// }
