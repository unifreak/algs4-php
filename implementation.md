## Algs E.4 PHP Code Note

<This is not completed yet, to be elaborated...>

### Programming model
Indexed array only, only when nessisary
尽量与原书变量名一致
Comparable -> sgh/comparable
Only Algs namespace

@todo When neccessary, use SplFixedArray (FixedCapacityStackOfStrings) -> Arr:
- 一般基于数组的, 用 Arr 代替
- 基于链表的则不是, 因为链表没有定长限制, 而且在 PHP 中对每种结点实现类型限制也比较麻烦
会导致 API 不一致, 比如 ST 的, 有些需要传入类型, 有些不需要

__constructStatic()

使用了 7.2 的特性

file larger than 100M are not uploaded due to github restriction
- leipzig1M.txt

function overload
- sort and doSort
- fromThis, fromThat
- getForm, putTo

做除法时一定要注意是否需要强转成 (int)
= null -> unset()

no Inner Class (Node)
hashCode()

### PHP Sucks?
- 泛型 (Stack<String>, Stack<Double>)
- 面向对象 (String.charAt())
- 多字节支持 ([] 对中文无用)
- `$$$$$$`, `$this`
- No hashCode() @see p.295 默认实现, 32bit, equals() 配合

    `Hashable` is an interface which allows objects to be used as keys. It’s an alternative to spl_object_hash(), which determines an object’s hash based on its handle
    - hash() is used to return a scalar value to be used as the object's hash value, which determines where it goes in the hash table
    - equals() is used to determine if two objects are equal

    https://stackoverflow.com/a/8521021/3776039 ?

    For a more portable solution you may also consider the generic hash(). hash("crc32b", $str) will return the same string as str_pad(dechex(crc32($str)), 8, '0', STR_PAD_LEFT).



## Code Style
尽量与原书一直: 缩进, 省略, 变量名

## 搭建, 运行

## Mock Java

- Static block: <https://stackoverflow.com/questions/3312806/static-class-initializer-in-php/3312881#3312881>
- 只实现了书中必要的库方法
- 数组引用
- main() -> **Test::run()

## Autoload:
- Composer autoload
- <https://stackoverflow.com/questions/41496727/how-to-force-php-cli-to-read-my-user-ini-file>
- <https://symfony.com/doc/current/components/var_dumper.html>
- .user.ini, bash_profile

## Composer packages